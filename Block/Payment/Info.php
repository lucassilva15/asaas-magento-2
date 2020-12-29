<?php
namespace Asaas\Magento2\Block\Payment;

class Info extends \Magento\Framework\View\Element\Template
{
	protected $_checkoutSession;
    protected $_orderFactory;
    protected $_scopeConfig;

    protected $_template = 'Asaas_Magento2::info/info.phtml';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        array $data = []
    ) {
		parent::__construct($context, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;
    }


    // Use this method to get ID
    public function getRealOrderId()
    {
        $lastorderId = $this->_checkoutSession->getLastOrderId();
        return $lastorderId;
    }

    public function getOrder()
    {
        if ($this->_checkoutSession->getLastRealOrderId()) {
            return $this->_checkoutSession->getLastRealOrder();
        }
        if ($order = $this->getInfo()->getOrder()) {
            return $order;
        }
        return false;
    }

	public function getPaymentMethod()
    {
		$payment = $this->_checkoutSession->getLastRealOrder()->getPayment();
		return $payment->getMethod();
	}

    public function getPaymentInfo()
    {
        $order = $this->getOrder();
        if ($payment = $order->getPayment()) {
			$paymentMethod = $payment->getMethod();
			switch($paymentMethod)
			{
				case 'boleto':
					return array(
						'tipo' => 'Boleto',
						'url' => $order->getBoletoAsaas(),
						'texto' => 'Clique aqui para imprimir seu boleto'
                       
					);
					break;
				case 'cc':
					return array(
						'tipo' => 'Cartão de Crédito'
						
					);
				break;
               
			}
		}
        return false;
    }
}