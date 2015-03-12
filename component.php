<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(CModule::IncludeModule("iblock")){
	$arResult=array();
	$arFilter=array("IBLOCK_ID"=>$arParams["IBLOCK_ID"]);
	if($arParams["USE_CODE"]=="Y"){
		$arURI=explode("/", $_SERVER["REQUEST_URI"]);
		$cnt=count($arURI);
		$dsa=explode("?", $arURI[$cnt-1]);
		$arURI[$cnt-1]=$dsa[0];
		if($arURI[$cnt-1]=="index.php" || $arURI[$cnt-1]==""){
			$code=$arURI[$cnt-2];
		}else{
			$code=$arURI[$cnt-2]."/".$arURI[$cnt-1];
		}
		$arFilter["CODE"]=$code;
	}else{
		$arFilter["ID"]=$arParams["PAGE_ID"];
	}
	$res=CIblockElement::GetList(array(), $arFilter, false, false, array("ID", "IBLOCK_ID", "NAME", "DETAIL_TEXT", "PROPERTY_UF_SEO_FLAG", "PROPERTY_UF_META_TITLE", "PROPERTY_UF_META_K", "PROPERTY_UF_META_D", "PROPERTY_UF_SEO_TEXT"));
	if($arRes = $res->GetNext()){
		$arResult["NAME"]=$arRes["NAME"];
		$arResult["TEXT"]=$arRes["DETAIL_TEXT"];
		$APPLICATION->SetPageProperty("title", $arRes["NAME"]);

		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arRes["IBLOCK_ID"], $arRes["ID"]);
		$arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();

		/*if ($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != "")
			$APPLICATION->SetTitle($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]);
		else
			$APPLICATION->SetTitle($arResult["NAME"]);*/

		if ($arResult["IPROPERTY_VALUES"]['ELEMENT_META_TITLE'] != '')
			$APPLICATION->SetPageProperty("title", $arResult["IPROPERTY_VALUES"]['ELEMENT_META_TITLE']);

		if ($arResult["IPROPERTY_VALUES"]['ELEMENT_META_KEYWORDS'] != '')
			$APPLICATION->SetPageProperty("keywords", $arResult["IPROPERTY_VALUES"]['ELEMENT_META_KEYWORDS']);

		if ($arResult["IPROPERTY_VALUES"]['ELEMENT_META_DESCRIPTION'] != '')
			$APPLICATION->SetPageProperty("description", $arResult["IPROPERTY_VALUES"]['ELEMENT_META_DESCRIPTION']);
	}else{
		$arResult["ERROR"][]="Page not found";
	}
	$this->IncludeComponentTemplate();
	if($arParams["ADD_SECTIONS_CHAIN"] && isset($arResult["PATH"]) && is_array($arResult["PATH"])){
		foreach($arResult["PATH"] as $arPath){
			$APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
		}
	}
}?>