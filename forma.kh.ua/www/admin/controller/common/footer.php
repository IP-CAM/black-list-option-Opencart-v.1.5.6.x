<?php
class ControllerCommonFooter extends Controller {   
	protected function index() {
		$this->load->language('common/footer');
		
		$this->data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);
		
		/* start loginza2 */
		if( 
			!empty( $this->request->get['route'] ) && 
			(
				$this->request->get['route']=='sale/customer/update' ||
				$this->request->get['route']=='sale/order/info' ||
				$this->request->get['route']=='sale/order/update' ||
				$this->request->get['route']=='sale/customer' ||
				$this->request->get['route']=='sale/order'
			)
		)
		{
			$this->load->model('sale/customer');
			$this->load->model('module/loginza2');
			$data =	$this->model_module_loginza2->showData();
			$this->data['text_footer'] .= $data;
		}
		/* end loginza2 */
		
		if (file_exists(DIR_SYSTEM . 'config/svn/svn.ver')) {
			$this->data['text_footer'] .= '.r' . trim(file_get_contents(DIR_SYSTEM . 'config/svn/svn.ver'));
		}
		
		$this->template = 'common/footer.tpl';
	
    	$this->render();
  	}
}
?>