<?php
class ControllerCommonJsonLdProduct extends Controller {
	public function index($data) {
        
        if(isset($data_["href"])){
            $data["url"] = $data_["href"];
        } else {
            $data["url"] = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }
        
        $data["sku"] = $data['product_id'];
        //$data["gtin"] = $data['product_id'];
        //$data["ISBN"] = $data['product_id'];
        
        if(isset($data["brand"])) $data["brand"] = $_SERVER['HTTP_HOST'];
        
        if(isset($data["priceMin"])){
            if($data["priceMin"] == $data["priceMax"]){
                $data["offers"]["Offer"]["price"] = $data["priceMin"];
            }else{
                $data["offers"]["AggregateOffer"]["lowPrice"] = $data["priceMin"];
                $data["offers"]["AggregateOffer"]["highPrice"] = $data["priceMax"];
            }           
        }else{
            $data["offers"]["Offer"]["price"] = $data["price"];
        }
        
        if ($data["statys"] == 1) {
            $data["offers"]["OfferItemCondition"] = "NewCondition";
        } elseif ($data["statys"] == 2) {
            $data["offers"]["OfferItemCondition"] = "UsedCondition";
        } elseif ($data["statys"] == 3) {
            $data["offers"]["OfferItemCondition"] = "DamagedCondition";
        } elseif ($data["statys"] == 4) {
            $data["offers"]["OfferItemCondition"] = "RefurbishedCondition";
        }
        
        $data["offers"]["offerCount"] = 999;
        $data["offers"]["priceCurrency"] = "UAH";
        $data["offers"]["availability"] = "PreOrder";
        $data["offers"]["priceValidUntil"] = ((int)Date("Y")+1)."-01-01";
        
        
        
        $data["color"] = false;//""        
        
        $data["additionalProperty"] = [];

        if(isset($data_["hum_byu"])){

            $data["aggregateRating"] = [
                "ratingValue" => $data_["rating"],
                "reviewCount" => $data_["hum_byu"]
            ];
        }else{
            $data["aggregateRating"] = [
                "ratingValue" => 5,
                "reviewCount" => 1
            ];
        }
        
          
        
        
        $data["audience"] = [];
        
        $data["award"] = [];
        
        $data["category"] = [];        
        
        
        $data["depth"] = [];
        
        $data["gtin12"] = [];
        
        $data["gtin13"] = [];
        
        $data["gtin14"] = [];
        
        $data["gtin8"] = [];        
        
        //Покажчик на інший продукт (або декілька продуктів), для якого цей продукт є аксесуаром або запасною частиною.
        $data["isAccessoryOrSparePartFor"] = [];
        
        $data["isConsumableFor"] = [];
        
        $data["isRelatedTo"] = [];
        
        $data["isSimilarTo"] = [];
        
        $data["itemCondition"] = [];
        
        $data["logo"] = [];
        
        $data["manufacturer"] = [];
        
        $data["material"] = [];
        
        $data["model"] = [];
        //Номер частини виробника (MPN) продукту або продукт, на який посилається пропозиція.
        $data["mpn"] = false;//"";
        
        //Ідентифікатор продукту, наприклад ISBN. Наприклад: meta itemprop="productID" content="isbn:123-456-789".
        $data["productID"] = false;//"";;
        
        $data["productionDate"] = [];
        
        $data["purchaseDate"] = [];
        
        $data["releaseDate"] = [];

        //Вага продукту
        $data["weight"] = [];
        
        $data["width"] = [];       
        
        $data["additionalType"] = [];
        
        $data["alternateName"] = [];
        
        $data["disambiguatingDescription"] = [];
        
        $data["identifier"] = [];

        $data["mainEntityOfPage"] = [];

        $data["potentialAction"] = [];
        
        $data["sameAs"] = [];
        
        $data["subjectOf"] = [];
        
        if(!$data["description"]) $data["description"] = $data["name"];

        $this->document->setJson_ld($this->load->view('common/json_ld/product', $data));
        
    }
}
