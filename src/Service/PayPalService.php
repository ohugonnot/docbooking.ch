<?php

namespace App\Service;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\Payer;
use PayPal\Api\ItemList;
use PayPal\Api\Item;

class PayPalService
{
    /**
     * @var ApiContext
     */
    private $apiContext;

    public function __construct()
    {
        $apiContext = new ApiContext(
          new OAuthTokenCredential(
              /*getenv('PAYPAL_CLIENT_ID'),
              getenv('PAYPAL_SECRET')*/
			  '',
			  ''
          )
        );

        $apiContext->setConfig([
            'mode'			 => 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName'	 => '../PayPal.log',
            'log.LogLevel'	 => 'DEBUG',
            'cache.enabled'	 => true,
            ]);

        $this->apiContext = $apiContext;
    }

	public function createPaymentFromOrder(array $order)
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
    }
}
