<?php
/* -----------------------------------------------------------------------------------------
   VamShop - http://vamshop.com
   -----------------------------------------------------------------------------------------
   Copyright (c) 2014 VamSoft Ltd.
   License - http://vamshop.com/license.html
   ---------------------------------------------------------------------------------------*/

echo $this->Form->input('cloudkassir.ck_inn', array(
	'label' => 'ИНН',
	'type' => 'text',
	'value' => $data['PaymentMethodValue'][0]['value']
	));

echo $this->Form->input('cloudkassir.ck_public_id', array(
	'label' => 'Публичный ключ',
	'type' => 'text',
	'value' => $data['PaymentMethodValue'][1]['value']
	));
	
echo $this->Form->input('cloudkassir.ck_secret_api', array(
	'label' => 'Секретный ключ',
	'type' => 'text',
	'value' => $data['PaymentMethodValue'][2]['value']
	));
	
echo $this->Form->input('cloudkassir.ck_taxationSystem', array(
	'label' => 'Система налогообложения',
	'type' => 'select',
	'value' => $data['PaymentMethodValue'][3]['value'],
	'options' => array(
	'0' => 'Общая система налогообложения',
	'1' => 'Упрощенная система налогообложения (Доход)',
	'2' => 'Упрощенная система налогообложения (Доход минус расход)',
	'3' => 'Единый налог на вмененный доход',
	'4' => 'Единый сельскохозяйственный налог',
	'5' => 'Патентная система налогообложения',
	),
	));
	
echo $this->Form->input('cloudkassir.ck_vat', array(
	'label' => 'Ставка НДС',
	'type' => 'select',
	'value' => $data['PaymentMethodValue'][4]['value'],
	'options' => array(
	'' => 'НДС не облагается',
	'20' => 'НДС 20%',
	'12' => 'НДС 12%',
	'10' => 'НДС 10%',
	'0' => 'НДС 0%',
	'110' => 'Расчетный НДС 10/110',
	'120' => 'Расчетный НДС 20/120',
	),
	));
	
echo $this->Form->input('cloudkassir.ck_vatd', array(
	'label' => 'Ставка НДС для доставки',
	'type' => 'select',
	'value' => $data['PaymentMethodValue'][5]['value'],
	'options' => array(
	'' => 'НДС не облагается',
	'20' => 'НДС 20%',
	'12' => 'НДС 12%',
	'10' => 'НДС 10%',
	'0' => 'НДС 0%',
	'110' => 'Расчетный НДС 10/110',
	'120' => 'Расчетный НДС 20/120',
	),
	));
	
?>