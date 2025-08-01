<?php

namespace App\Service;

use PayPal\Rest\ApiContext;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;

class PayPalV2Service
{
    /**
     * @var ApiContext
     */
    private $client;

    public function __construct()
    {
		$clientId = "";
		$clientSecret = "";
		$environment = new SandboxEnvironment($clientId, $clientSecret);
		$this->client = new PayPalHttpClient($environment);
    }

	public function createOrder(array $order)
    {
		$request = new OrdersCreateRequest();
		$request->prefer('return=representation');
		$request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $order['order']["sku"],
                    "amount" => [
                        "value" => $order['order']["price"],
                        "currency_code" => "EUR"
                    ]
                ]],
                "application_context" => [
                    "cancel_url" => $order['order']["failure_url"],
                    "return_url" => $order['order']["success_url"]
                ]
        ];

		try {
			// Call API with your client and get a response for your call
			$response = $this->client->execute($request);
			// If call returns body in response, you can get the deserialized version from the result attribute of the response
			return $response;
		}
		catch (HttpException $ex) {
			echo $ex->statusCode;
			print_r($ex->getMessage());
		}
	}

	public function captureOrder(array $order)
    {
		// Here, OrdersCaptureRequest() creates a POST request to /v2/checkout/orders
		// $response->result->id gives the orderId of the order created above
		$request = new OrdersCaptureRequest("APPROVED-ORDER-ID");
		$request->prefer('return=representation');
		try {
			// Call API with your client and get a response for your call
			$response = $this->client->execute($request);
			// If call returns body in response, you can get the deserialized version from the result attribute of the response
			print_r($response);
		}
		catch (HttpException $ex) {
			echo $ex->statusCode;
			print_r($ex->getMessage());
		}
	}

	/*public function createPaymentFromOrder(array $order)
    {
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        $itemList = new ItemList();

        foreach ($order['order']['items'] as $item) {
			$orderItem = new Item();
			$orderItem->setName($item["name"])
					  ->setCurrency("EUR")
					  ->setQuantity(1)
					  ->setSKU($item["sku"])
					  ->setPrice($item["price"]);
			$itemList->addItem($orderItem);
        }

        $details = new Details();
        $details->setTax($order['order']["tax"])
				->setSubtotal($order['order']["subtotal"]);

        $amount = new Amount();
        $amount->setCurrency("EUR")
				->setTotal($order['order']["total"])
				->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($order['order']["success_url"])
					 ->setCancelUrl($order['order']["failure_url"]);

        $payment = new Payment();
        $payment->setIntent("sale")
				->setPayer($payer)
				->setRedirectUrls($redirectUrls)
				->setTransactions(array($transaction));

	    try {
            $payment->create($this->apiContext);
        }
	    catch (\Exception $exception) {
            var_dump($exception->getMessage());
            exit(1);
        }

        return $payment->getApprovalLink();
    }*/
}
