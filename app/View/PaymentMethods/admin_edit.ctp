<?php
/* -----------------------------------------------------------------------------------------
   VamShop - http://vamshop.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2014 VamSoft Ltd.
   License - http://vamshop.com/license.html
   ---------------------------------------------------------------------------------------*/

$this->Html->script(array(
	'admin/modified.js',
	'admin/focus-first-input.js'
), array('inline' => false));

	echo $this->Admin->ShowPageHeaderStart($current_crumb, 'cus-application-edit');

	echo $this->Form->create('PaymentMethod', array('id' => 'contentform', 'url' => '/payment_methods/admin_edit/' . $data['PaymentMethod']['id']));
	echo $this->Form->input('PaymentMethod.id', 
						array(
				   		'type' => 'hidden',
							'value' => $data['PaymentMethod']['id']
	               ));
	echo $this->Form->input('PaymentMethod.name', 
						array(
				   		'label' => __('Name'),
   						'value' => $data['PaymentMethod']['name']
	               ));				     				   	   																									
	
    if ($data['PaymentMethod']['name']!='Cloudkassir')//убираем лишние настройки в cloudkassir
    {
        
        echo $this->Form->input('PaymentMethod.description', 
						array(
							'type' => 'textarea',
							'label' => __('Description'),
							'class' => 'pagesmalltextarea',
							'value' => $data['PaymentMethod']['description']
	               ));	
	    echo $this->Form->input('PaymentMethod.order', 
						array(
				   		'label' => __('Sort Order'),
   						'value' => $data['PaymentMethod']['order']
	               ));				     				   	   																									
	    echo $this->Form->input('PaymentMethod.order_status_id', 
						array(
							'type' => 'select',
							'label' => __('Order Status'),
							'options' => $order_status_list,
							'selected' => $current_order_status
	               ));
	
	//фильтр активацииcloudkassir
	if ($data['PaymentMethod']['alias']!='Invoice' and $data['PaymentMethod']['alias']!='Kvitancia' and $data['PaymentMethod']['alias']!='CreditCard'
	and $data['PaymentMethod']['alias']!='MoneyOrderCheck' and $data['PaymentMethod']['alias']!='StorePickup' and $data['PaymentMethod']['alias']!='Bitcoin'
	and $data['PaymentMethod']['alias']!='Ethereum')
	{
        if ($data['PaymentMethod']['cloudkassir']==1) $checked=checked;

        echo $this->Form->input('PaymentMethod.cloudkassir', array(
	            'type' => 'checkbox',
				$checked,
				'label' => 'Отправлять чеки в онлайн-кассу с Cloudkassir'
	    ));
	} 
    //фильтр активацииcloudkassir
    
    };
	
	echo $this->requestAction( '/payment/'.$data['PaymentMethod']['alias'].'/settings/', array('return'));	
	
	echo '<div class="clear"></div>';
	echo $this->Admin->formButton(__('Submit'), 'cus-tick', array('class' => 'btn btn-primary', 'type' => 'submit', 'name' => 'submit')) . $this->Admin->formButton(__('Cancel'), 'cus-cancel', array('class' => 'btn btn-default', 'type' => 'submit', 'name' => 'cancelbutton'));
	echo $this->Form->end();
	
	echo $this->Admin->ShowPageHeaderEnd();
	
	?>