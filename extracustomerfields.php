<?php
/**
 * 2013-2023 vallka
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 *  @author    vallka
 *  @copyright 2013-2023 vallka
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

use Symfony\Component\Form\Extension\Core\Type\TextType;

class ExtraCustomerFields extends Module
{
    public function __construct()
    {
        $this->name = 'extracustomerfields';
        $this->author = 'vallka';
        $this->version = '1.0.0';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->tab = 'others';
        parent::__construct();

        $this->displayName = $this->l('Extra customer fields');
        $this->ps_versions_compliancy = array(
            'min' => '1.7',
            'max' => _PS_VERSION_
        );
        $this->description = $this->l('Add extra customer fields');
    }

    /**
     * Install module
     *
     * @return bool true if success
     */
    public function install()
    {
        if (!parent::install()
            || !$this->createExtraCustomerFieldsTable()
            || !$this->registerHook('additionalCustomerFormFields')
            || !$this->registerHook('validateCustomerFormFields')
            || !$this->registerHook('actionObjectCustomerUpdateAfter')
            || !$this->registerHook('actionObjectCustomerAddAfter')
            || !$this->registerHook('actionCustomerFormBuilderModifier')
        ) {
            return false;
        }
        return true;
    }

    /**
     * Uninstall module
     *
     * @return bool true if success
     */
    public function uninstall()
    {
        if (!parent::uninstall()
            || !$this->deleteExtraCustomerFieldsTable()
        ) {
            return false;
        }
        return true;
    }

    /**
     * Create extra customer fields table
     *
     * @return bool true if success or already done.
     */
    protected function createExtraCustomerFieldsTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extra_customer_fields` (
            `id_customer` int(10) unsigned NOT NULL,
            `facebook` varchar(255) DEFAULT NULL,
            `instagram` varchar(255) DEFAULT NULL,
            `twitter` varchar(255) DEFAULT NULL,
            `whatsapp` varchar(255) DEFAULT NULL,
            `telegram` varchar(255) DEFAULT NULL,
            `preferred_communication_method` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id_customer`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        return Db::getInstance()->execute($sql);
    }

    /**
     * Delete extra customer fields table
     *
     * @return bool true if success
     */
    protected function deleteExtraCustomerFieldsTable()
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'extra_customer_fields`;';
        return Db::getInstance()->execute($sql);
    }


    /**
     * Read module fields values
     *
     * @return array of module values
     */
    protected function readModuleValues($id_customer)
    {
        $query = 'SELECT `facebook`, `instagram`, `twitter`, `whatsapp`, `telegram`, `preferred_communication_method`'
            .' FROM `'._DB_PREFIX_.'extra_customer_fields`'
            .' WHERE `id_customer` = '.(int)$id_customer;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
    }

    /**
     * Write module fields values
     *
     * @param int $id_customer Customer ID
     * @return void
     */
    protected function writeModuleValues($id_customer)
    {
        if ($this->context->controller->controller_type === 'admin') {
            //TODO: update from admin. Not needed right now
        }
        else {
            $facebook = Tools::getValue('facebook');
            $instagram = Tools::getValue('instagram');
            $twitter = Tools::getValue('twitter');
            $whatsapp = Tools::getValue('whatsapp');
            $telegram = Tools::getValue('telegram');
            $preferred_communication_method = Tools::getValue('preferred_communication_method');
    
            $query = 'INSERT INTO `'._DB_PREFIX_.'extra_customer_fields` (`id_customer`, `facebook`, `instagram`, `twitter`, `whatsapp`, `telegram`, `preferred_communication_method`)'
                    .' VALUES ('.(int)$id_customer.', "'.pSQL($facebook).'", "'.pSQL($instagram).'", "'.pSQL($twitter).'", "'.pSQL($whatsapp).'", "'.pSQL($telegram).'", "'.pSQL($preferred_communication_method).'")'
                    .' ON DUPLICATE KEY UPDATE `facebook` = "'.pSQL($facebook).'", `instagram` = "'.pSQL($instagram).'", `twitter` = "'.pSQL($twitter).'", `whatsapp` = "'.pSQL($whatsapp).'", `telegram` = "'.pSQL($telegram).'", `preferred_communication_method` = "'.pSQL($preferred_communication_method).'"';
    
            Db::getInstance()->execute($query);
        }
    }

    /**
     * Add fields in Customer Form
     *
     * @param array $params parameters (@see CustomerFormatter->getFormat())
     *
     * @return array of extra FormField
     */
    public function hookAdditionalCustomerFormFields($params)
    {
        $id_customer = Context::getContext()->customer->id;

        $row = $this->readModuleValues($id_customer);

        $extra_fields = [];

        $extra_fields['facebook'] = (new FormField)
            ->setName('facebook')
            ->setType('text')
            ->setValue(isset($row['facebook']) ? $row['facebook'] : '')
            ->setLabel($this->l('Facebook'));

        $extra_fields['instagram'] = (new FormField)
            ->setName('instagram')
            ->setType('text')
            ->setValue(isset($row['instagram']) ? $row['instagram'] : '')
            ->setLabel($this->l('Instagram'));

        $extra_fields['twitter'] = (new FormField)
            ->setName('twitter')
            ->setType('text')
            ->setValue(isset($row['twitter']) ? $row['twitter'] : '')
            ->setLabel($this->l('Twitter'));

        $extra_fields['whatsapp'] = (new FormField)
            ->setName('whatsapp')
            ->setType('text')
            ->setValue(isset($row['whatsapp']) ? $row['whatsapp'] : '')
            ->setLabel($this->l('WhatsApp'));

        $extra_fields['telegram'] = (new FormField)
            ->setName('telegram')
            ->setType('text')
            ->setValue(isset($row['telegram']) ? $row['telegram'] : '')
            ->setLabel($this->l('Telegram'));

        $extra_fields['preferred_communication_method'] = (new FormField)
            ->setName('preferred_communication_method')
            ->setType('text')
            ->setValue(isset($row['preferred_communication_method']) ? $row['preferred_communication_method'] : '')
            ->setLabel($this->l('Preferred Communication Method'));

        return $extra_fields;
    }


    /**
     * Hook for validating additional fields in customer form
     *
     * @param array $params
     */
    public function hookValidateCustomerFormFields($params)
    {
        $errors = array();

        if (empty($params['form_data']['facebook']) && empty($params['form_data']['instagram']) && empty($params['form_data']['twitter']) && empty($params['form_data']['whatsapp']) && empty($params['form_data']['telegram'])) {
            $errors[] = $this->l('Please fill in at least one social media profile.');
        }

        if (empty($params['form_data']['preferred_communication_method'])) {
            $errors[] = $this->l('Please select a preferred communication method.');
        }

        return $errors;
    }


    public function hookActionObjectCustomerAddAfter($params)
    {
        $customer = $params['object'];
        $this->writeModuleValues($customer->id);
    }

    public function hookActionObjectCustomerUpdateAfter($params)
    {
        $customer = $params['object'];
        $this->writeModuleValues($customer->id);
    }

    public function hookActionCustomerFormBuilderModifier(array $params)
    {
        // readonly for now
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $params['form_builder'];
    
        $formBuilder->add('facebook', TextType::class, ['label' => 'Facebook','required' => false, 'disabled' => true]);
        $formBuilder->add('instagram', TextType::class, ['label' => 'Instagram','required' => false, 'disabled' => true]);
        $formBuilder->add('twitter', TextType::class, ['label' => 'Twitter','required' => false, 'disabled' => true]);
        $formBuilder->add('whatsapp', TextType::class, ['label' => 'Whatsapp','required' => false, 'disabled' => true]);
        $formBuilder->add('telegram', TextType::class, ['label' => 'Telegram','required' => false, 'disabled' => true]);
        $formBuilder->add('preferred_communication_method', TextType::class, ['label' => 'Preferred Communication Method','required' => false, 'disabled' => true]);

        $customerId = $params['id'];

        $data = $this->readModuleValues($customerId);

        $params['data']['facebook'] = $data['facebook'];
        $params['data']['instagram'] = $data['instagram'];
        $params['data']['twitter'] = $data['twitter'];
        $params['data']['whatsapp'] = $data['whatsapp'];
        $params['data']['telegram'] = $data['telegram'];
        $params['data']['preferred_communication_method'] = $data['preferred_communication_method'];

        $formBuilder->setData($params['data']);
    }
}




