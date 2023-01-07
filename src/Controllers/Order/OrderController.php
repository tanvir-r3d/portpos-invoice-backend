<?php

namespace App\Controllers\Order;

use App\Controllers\BaseController;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\PortPosService\PortPosService;
use GuzzleHttp\Exception\GuzzleException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class OrderController extends BaseController
{

    public function index(Request $request, Response $response): Response
    {
        $formData = $request->getParsedBody();
        $page = $formData['page'] ?? 1;
        $orders = Order::paginate(15, ['*'], 'page', $page);

        return $this->successResponse($response, $orders, 'Successfully fetched');
    }

    /**
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
        $order->status = OrderStatus::STATUS_PENDING->value;
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
            $responseBody = json_decode($clientResponse->getBody()->getContents(), true);
            $order->invoice_id = $responseBody['data']['invoice_id'];
            $order->save();
        }

        return $this->successResponse($response,
            $clientResponse->getBody()->getContents(),
            'Successfully generated invoice');
    }

    public function updateStatus(Response $response, $id, $status): Response
    {
        try {
            $order = Order::findOrFail($id);
            $order->status = $status;
            $order->save();

            return $this->successResponse($response, $order, 'Successfully Status Updated');
        } catch (\Exception $exception) {
            return $this->errorResponse($response, $exception->getMessage(), $exception->getCode());
        }
    }
}