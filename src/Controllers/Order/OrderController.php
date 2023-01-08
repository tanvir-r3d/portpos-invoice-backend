<?php

namespace App\Controllers\Order;

use App\Controllers\BaseController;
use App\Models\Order;
use App\Services\PortPosService\PortPosService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class OrderController extends BaseController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        $formData = $request->getParsedBody();
        $page = $formData['page'] ?? 1;
        $orders = Order::paginate(15, ['*'], 'page', $page);

        return $this->successResponse($response, $orders, 'Successfully fetched');
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws GuzzleException
     */
    public function store(Request $request, Response $response): Response
    {
        $formBody = $request->getParsedBody();
        $orderForm = $formBody['order'];
        $product = $formBody['product'];
        $billing = $formBody['billing'];
        $customer = $billing['customer'];
        $address = $customer['address'];

        $order = new Order();
        $order->status = 0;
        $order->fill($orderForm)->save();

        $order->billing()->create([
            'order_id' => $order->id,
            'name' => $customer['name'],
            'email' => $customer['email'],
            'phone' => $customer['phone'],
            'address_street' => $address['street'],
            'address_city' => $address['city'],
            'address_state' => $address['state'],
            'address_zipcode' => $address['zipcode'],
            'address_country' => $address['country'],
        ]);

        $order->product()->create($product);

        $clientResponse = PortPosService::init()
            ->setBody($formBody)
            ->generateInvoice();
        if ($clientResponse->getStatusCode() === 201) {
            $body = json_decode($clientResponse->getBody()->getContents(), true);
            if (isset($body['data']['invoice_id'])) :
                $order->invoice_id = $body['data']['invoice_id'];
            endif;
            $order->save();

            return $this->successResponse(
                $response,
                $order->invoice_id,
                'Successfully generated invoice'
            );
        }
        return $this->errorResponse(
            $response,
            'Successfully generated invoice'
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function updateStatus(Request $request, Response $response, $args): Response
    {
        try {
            $order = Order::findOrFail($args['id']);
            $order->status = $args['status'];
            $order->save();

            return $this->successResponse($response, $order, 'Successfully Status Updated');
        } catch (Exception $exception) {
            return $this->errorResponse($response, $exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws GuzzleException
     */
    public function fetchIpn(Request $request, Response $response, $args): Response
    {
        try {
            $order = Order::findOrFail($args['id']);
            $invoiceId = $order->invoice_id;
            $amount = $order->amount;
            $clientResponse = PortPosService::init()->getIPN($invoiceId, $amount);

            return $this->successResponse(
                $response,
                json_decode(
                    $clientResponse->getBody()->getContents(),
                    true
                ),
                'Successfully Fetched IPN'
            );
        } catch (Exception $exception) {
            return $this->errorResponse($response, $exception->getMessage(), $exception->getCode());
        }
    }
}
