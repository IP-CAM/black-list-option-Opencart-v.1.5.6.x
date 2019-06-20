<?php
class ControllerCommonJsonLdBreadcrumblist extends Controller {
    
	public function index($data_) {
        
        $data_[0] = array(
			'text' => "Home",
			'href' => $this->url->link('common/home')
		);
        
        $data['breadcrumbs'] = $data_;
        
        $breadcrumbTexts = array();
        
        $i = 0;
        foreach ($breadcrumbs as $value) {
            $breadcrumbTexts[] = '{"@type": "ListItem","position": '.$i++.',"item":"@id": "'.$value['href'].'","name": "'.$value['text'].'"}}';
        }
        
        $this->data['breadcrumbList'] = join($breadcrumbTexts, ',');
        
        if(!empty($this->data['breadcrumbList'])) return;
        
        $this->document->setJson_ld($this->load->view('common/json_ld/breadcrumblist', $data));
    }
}
