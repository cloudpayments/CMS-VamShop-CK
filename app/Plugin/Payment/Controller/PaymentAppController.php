<?php
/* -----------------------------------------------------------------------------------------
   VamShop - http://vamshop.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2014 VamSoft Ltd.
   License - http://vamshop.com/license.html
   ---------------------------------------------------------------------------------------*/
App::uses('AppController', 'Controller');

class PaymentAppController extends AppController 
{

//отправка запроса
public  function send_receipt($order_id,$Type)
{   
    //заказ
    $order                      = $this->Order->read(null,$order_id);
    //метод оплаты
	$payment_method             = $order['Order']['payment_method_id'];
    
    //API из настроек
    $ck_secret_api_settings     = $this->PaymentMethod->PaymentMethodValue->find('first', array('conditions' => array('key' => 'ck_secret_api')));
    $ck_API                     = $ck_secret_api_settings['PaymentMethodValue']['value'];
    //публичный ключ
    $ck_public_id_settings      = $this->PaymentMethod->PaymentMethodValue->find('first', array('conditions' => array('key' => 'ck_public_id')));
	$ck_PublicId                = $ck_public_id_settings['PaymentMethodValue']['value'];
    //инн
    $ck_inn_settings            = $this->PaymentMethod->PaymentMethodValue->find('first', array('conditions' => array('key' => 'ck_inn')));
	$ck_INN                     = $ck_inn_settings['PaymentMethodValue']['value'];
	//система налогообложения
	$ck_taxationSystem_settings = $this->PaymentMethod->PaymentMethodValue->find('first', array('conditions' => array('key' => 'ck_taxationSystem')));
	$ck_taxationSystem          = $ck_taxationSystem_settings['PaymentMethodValue']['value'];
	//НДС
	$ck_vat_settings            = $this->PaymentMethod->PaymentMethodValue->find('first', array('conditions' => array('key' => 'ck_vat')));
	$ck_vat                     = $ck_vat_settings['PaymentMethodValue']['value'];
	//НДС доставки
	$ck_vatd_settings           = $this->PaymentMethod->PaymentMethodValue->find('first', array('conditions' => array('key' => 'ck_vatd')));
	$ck_vatd                    = $ck_vatd_settings['PaymentMethodValue']['value'];
	//сумма заказа
	$amount                     = $order['Order']['total'];
    //id плательщика
    $accountId                  = $order['Order']['bill_name'];

    //формируем чек
        foreach ($order['OrderProduct'] as $product)
        {      
            $Items[] = array(
            'label'           =>trim($product['name']),
            'price'           =>$product['price'],
            'quantity'        =>$product['quantity'],
            'amount'          =>$product['price']*$product['quantity'],
            'vat'             =>$ck_vat,
            'method'          =>0,
    	    'object'          =>0,
            'measurementUnit' =>'шт.',
            );
        };        
        if ($order['Order']['shipping'] > 0)
        {   $Items[] = array(
            'label'           =>trim('Доставка - ' . __($order['ShippingMethod']['name'])),
            'price'           =>$order['Order']['shipping'],
            'quantity'        =>1,
            'amount'          =>$order['Order']['shipping'],
            'vat'             =>$ck_vatd,
            'method'          =>0,
    	    'object'          =>0,
            'measurementUnit' =>'',
            );
        };
        $receipt = array(
            'Items'         =>$Items,
            'taxationSystem'=>$ck_taxationSystem,
	        'email'         =>$order['Order']['email'],
	        'phone'         =>$order['Order']['phone'],
	        'amounts'       =>array (
	        'electronic'     => $order['Order']['total'],
		    'advancePayment' => 0,
		    'credit'         => 0,
		    'provision'      => 0,
	        ),
	    );
        $data = array(
            'Inn'              => $ck_INN, //ИНН
            'InvoiceId'        => $order_id, //номер заказа, необязательный
            'AccountId'        => $accountId, //идентификатор пользователя, необязательный
            'Type'             => $Type, //признак расчета
            'CustomerReceipt'  => $receipt,
            );
        //отправляем запрос
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD,$ck_PublicId. ':' . $ck_API);
        curl_setopt($ch, CURLOPT_URL, 'https://api.cloudpayments.ru/kkt/receipt');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array  (
                                                     'content-type: application/json',
                                                     'X-Request-ID:'.$order_id.$Type)
                                                    );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_exec($ch);
        curl_close($ch);
}
    

}
?>