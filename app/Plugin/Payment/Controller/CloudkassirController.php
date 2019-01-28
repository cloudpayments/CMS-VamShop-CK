<?php 

App::uses('PaymentAppController', 'Payment.Controller');

class CloudkassirController extends PaymentAppController {
	public $uses = array('PaymentMethod', 'Order');
	public $module_name = 'cloudkassir';

	public function settings ()
	{
		$this->set('data', $this->PaymentMethod->findByAlias($this->module_name));
	}

	public function install()
	{
		$new_module = array();
		$new_module['PaymentMethod']['active'] = '1';
		$new_module['PaymentMethod']['default'] = '0';
		$new_module['PaymentMethod']['name'] = Inflector::humanize($this->module_name);
		$new_module['PaymentMethod']['alias'] = $this->module_name;

        $new_module['PaymentMethodValue'][0]['payment_method_id'] = $this->PaymentMethod->id;
		$new_module['PaymentMethodValue'][0]['key'] = 'ck_inn';
		$new_module['PaymentMethodValue'][0]['value'] = '';

		$new_module['PaymentMethodValue'][1]['payment_method_id'] = $this->PaymentMethod->id;
		$new_module['PaymentMethodValue'][1]['key'] = 'ck_public_id';
		$new_module['PaymentMethodValue'][1]['value'] = '';

		$new_module['PaymentMethodValue'][2]['payment_method_id'] = $this->PaymentMethod->id;
		$new_module['PaymentMethodValue'][2]['key'] = 'ck_secret_api';
		$new_module['PaymentMethodValue'][2]['value'] = '';
		
		$new_module['PaymentMethodValue'][3]['payment_method_id'] = $this->PaymentMethod->id;
		$new_module['PaymentMethodValue'][3]['key'] = 'ck_taxationSystem';
		$new_module['PaymentMethodValue'][3]['value'] = '';
		
		$new_module['PaymentMethodValue'][4]['payment_method_id'] = $this->PaymentMethod->id;
		$new_module['PaymentMethodValue'][4]['key'] = 'ck_vat';
		$new_module['PaymentMethodValue'][4]['value'] = '';
		
		$new_module['PaymentMethodValue'][5]['payment_method_id'] = $this->PaymentMethod->id;
		$new_module['PaymentMethodValue'][5]['key'] = 'ck_vatd';
		$new_module['PaymentMethodValue'][5]['value'] = '';
		
		$this->PaymentMethod->saveAll($new_module);

		$this->Session->setFlash(__('Module Installed'));
		$this->redirect('/payment_methods/admin/');
	}

	public function uninstall()
	{

		$module_id = $this->PaymentMethod->findByAlias($this->module_name);

		$this->PaymentMethod->delete($module_id['PaymentMethod']['id'], true);
			
		$this->Session->setFlash(__('Module Uninstalled'));
		$this->redirect('/payment_methods/admin/');
	}
	public  function manual_send_receipt() 
    {
        $order_id = $_POST['order_id'];
        $Type     = $_POST['Type'];
        $order    = $this->Order->read(null,$order_id);
        
	    $this->send_receipt($order_id,$Type); 
        
        if ($Type == 'IncomeReturn')
        {
            $order['Order']['cloudkassir'] = 0;
        }
        elseif ($Type == 'Income')
        {
            $order['Order']['cloudkassir'] = 1;};
            
        $this->Order->save($order);
        $this->redirect('/orders/admin_view/'.$order_id);
    } 
	
	public function result()
	{
	    $this->layout = false;
	    
        //API из настроек
        $ck_secret_api_settings     = $this->PaymentMethod->PaymentMethodValue->find('first', array('conditions' => array('key' => 'ck_secret_api')));
        $ck_API                     = $ck_secret_api_settings['PaymentMethodValue']['value'];
        
        //контрольная подпись
        $hash   = $_POST;
        $sign   = hash_hmac('sha256', $hash, $ck_API, true);
        $sign   = base64_encode($sign);
        $signs  = $_SERVER['HTTPS_CONTENT_HMAC'];
        
        //проверяем контрольную подпись
        if ($signs!= $sign)
        {
            $this->response->body(json_encode(array('code'=>13)));
        }
        else
        {
            $this->response->body(json_encode(array('code'=>0)));
        };   
        
        return $this->response;
    }
	
}

?>