<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$templateData = array(
    'TEMPLATE_THEME' => $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css',
    'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME']
);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
    'ID' => $strMainID,
    'PICT' => $strMainID . '_pict',
    'DISCOUNT_PICT_ID' => $strMainID . '_dsc_pict',
    'STICKER_ID' => $strMainID . '_sticker',
    'BIG_SLIDER_ID' => $strMainID . '_big_slider',
    'BIG_IMG_CONT_ID' => $strMainID . '_bigimg_cont',
    'SLIDER_CONT_ID' => $strMainID . '_slider_cont',
    'SLIDER_LIST' => $strMainID . '_slider_list',
    'SLIDER_LEFT' => $strMainID . '_slider_left',
    'SLIDER_RIGHT' => $strMainID . '_slider_right',
    'OLD_PRICE' => $strMainID . '_old_price',
    'PRICE' => $strMainID . '_price',
    'DISCOUNT_PRICE' => $strMainID . '_price_discount',
    'SLIDER_CONT_OF_ID' => $strMainID . '_slider_cont_',
    'SLIDER_LIST_OF_ID' => $strMainID . '_slider_list_',
    'SLIDER_LEFT_OF_ID' => $strMainID . '_slider_left_',
    'SLIDER_RIGHT_OF_ID' => $strMainID . '_slider_right_',
    'QUANTITY' => $strMainID . '_quantity',
    'QUANTITY_DOWN' => $strMainID . '_quant_down',
    'QUANTITY_UP' => $strMainID . '_quant_up',
    'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
    'QUANTITY_LIMIT' => $strMainID . '_quant_limit',
    'BUY_LINK' => $strMainID . '_buy_link',
    'ADD_BASKET_LINK' => $strMainID . '_add_basket_link',
    'COMPARE_LINK' => $strMainID . '_compare_link',
    'PROP' => $strMainID . '_prop_',
    'PROP_DIV' => $strMainID . '_skudiv',
    'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
    'OFFER_GROUP' => $strMainID . '_set_group_',
    'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
);
$strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
    : $arResult['NAME']
);
$strAlt = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
    : $arResult['NAME']
);
?>
<div class="bx_item_detail <? echo $templateData['TEMPLATE_CLASS']; ?>" id="<? echo $arItemIDs['ID']; ?>">
    <?
    if ('Y' == $arParams['DISPLAY_NAME']) {
        ?>
        <div class="bx_item_title"><h1><span><?
                    echo(
                    isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
                        ? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
                        : $arResult["NAME"]
                    ); ?>
</span></h1></div>
    <?
    }
    reset($arResult['MORE_PHOTO']);
    $arFirstPhoto = current($arResult['MORE_PHOTO']);
    ?>
    <div class="bx_item_container">
        <div class="bx_lt">
            <div class="bx_item_slider" id="<? echo $arItemIDs['BIG_SLIDER_ID']; ?>">
                <div class="bx_bigimages" id="<? echo $arItemIDs['BIG_IMG_CONT_ID']; ?>">
                    <div class="bx_bigimages_imgcontainer">
                        <span class=""><a href="#" class="zoom fancybox"><img
                                    id="<? echo $arItemIDs['PICT']; ?>" src="<? echo $arFirstPhoto['SRC']; ?>"
                                    alt="<? echo $strAlt; ?>" title="<? echo $strTitle; ?>"></a></span>
                        <?
                        if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']) {
                            if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS'])) {
                                if (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']) {
                                    ?>
                                    <div class="bx_stick_disc right bottom"
                                         id="<? echo $arItemIDs['DISCOUNT_PICT_ID'] ?>"><? echo $arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>
                                        %
                                    </div>
                                <?
                                }
                            } else {
                                ?>
                                <div class="bx_stick_disc right bottom" id="<? echo $arItemIDs['DISCOUNT_PICT_ID'] ?>"
                                     style="display: none;"></div>
                            <?
                            }
                        }
                        if ($arResult['LABEL']) {
                            ?>
                            <div class="bx_stick average left top" id="<? echo $arItemIDs['STICKER_ID'] ?>"
                                 title="<? echo $arResult['LABEL_VALUE']; ?>"><? echo $arResult['LABEL_VALUE']; ?></div>
                        <?
                        }
                        ?>
                    </div>
                </div>
                <?
                if ($arResult['SHOW_SLIDER']) {
                    if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS'])) {
                        if (5 < $arResult['MORE_PHOTO_COUNT']) {
                            $strClass = 'bx_slider_conteiner full';
                            $strOneWidth = (100 / $arResult['MORE_PHOTO_COUNT']) . '%';
                            $strWidth = (20 * $arResult['MORE_PHOTO_COUNT']) . '%';
                            $strSlideStyle = '';
                        } else {
                            $strClass = 'bx_slider_conteiner';
                            $strOneWidth = '20%';
                            $strWidth = '80px';
                            $strNewWidth = '86px';
                            $strNewHeight = '130px';
                            $strSlideStyle = 'display: none;';
                        }
                        ?>
                        <div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['SLIDER_CONT_ID']; ?>">
                            <div class="bx_slider_scroller_container">
                                <div class="bx_slide">
                                    <ul style="width: <? echo $strWidth; ?>;"
                                        id="<? echo $arItemIDs['SLIDER_LIST']; ?>">
                                        <?
                                        foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto) {
                                            ?>
                                            <li data-value="<? echo $arOnePhoto['ID']; ?>"
                                                style="width: <? echo $strNewWidth; ?>; height: <? echo $strNewHeight;?>;">
                                                <span class="cnt"><span  class="cnt_item"
                                                                        style="background-image:url('<? echo $arOnePhoto['SRC']; ?>');"></span></span>
                                            </li>
                                        <?
                                        }
                                        unset($arOnePhoto);
                                        ?>
                                    </ul>
                                </div>
                                <div class="bx_slide_left" id="<? echo $arItemIDs['SLIDER_LEFT']; ?>"
                                     style="<? echo $strSlideStyle; ?>"></div>
                                <div class="bx_slide_right" id="<? echo $arItemIDs['SLIDER_RIGHT']; ?>"
                                     style="<? echo $strSlideStyle; ?>"></div>
                            </div>
                        </div>
                    <?
                    } else {
                        foreach ($arResult['OFFERS'] as $key => $arOneOffer) {
                            if (!isset($arOneOffer['MORE_PHOTO_COUNT']) || 0 >= $arOneOffer['MORE_PHOTO_COUNT'])
                                continue;
                            $strVisible = ($key == $arResult['OFFERS_SELECTED'] ? '' : 'none');
                            if (5 < $arOneOffer['MORE_PHOTO_COUNT']) {
                                $strClass = 'bx_slider_conteiner full';
                                $strOneWidth = (100 / $arOneOffer['MORE_PHOTO_COUNT']) . '%';
                                $strWidth = (20 * $arOneOffer['MORE_PHOTO_COUNT']) . '%';
                                $strSlideStyle = '';
                            } else {
                                $strClass = 'bx_slider_conteiner';
                                $strOneWidth = '20%';
                                $strWidth = '80px';
                                $strNewWidth = '86px';
                                $strNewHeight = '130px';
                                $strSlideStyle = 'display: none;';
                            }
                            ?>
                            <div class="<? echo $strClass; ?>"
                                 id="<? echo $arItemIDs['SLIDER_CONT_OF_ID'] . $arOneOffer['ID']; ?>"
                                 style="display: <? echo $strVisible; ?>;">

                                <div class="bx_slider_scroller_container">
                                    <div class="bx_slide">
                                        <ul style="width: <? echo $strWidth; ?>;"
                                            id="<? echo $arItemIDs['SLIDER_LIST_OF_ID'] . $arOneOffer['ID']; ?>">
                                            <?
                                            foreach ($arOneOffer['MORE_PHOTO'] as &$arOnePhoto) {
                                                ?>
                                                <li data-value="<? echo $arOneOffer['ID'] . '_' . $arOnePhoto['ID']; ?>"
                                                    style="width: <? echo $strNewWidth; ?>; height: <? echo $strNewHeight; ?>">
                                                    <span class="cnt"><span class="cnt_item"
                                                                            style="background-image:url('<? echo $arOnePhoto['SRC']; ?>');"></span>
                                                    </span>
                                                </li>
                                            <?
                                            }
                                            unset($arOnePhoto);
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="bx_slide_left"
                                         id="<? echo $arItemIDs['SLIDER_LEFT_OF_ID'] . $arOneOffer['ID'] ?>"
                                         style="<? echo $strSlideStyle; ?>"
                                         data-value="<? echo $arOneOffer['ID']; ?>"></div>
                                    <div class="bx_slide_right"
                                         id="<? echo $arItemIDs['SLIDER_RIGHT_OF_ID'] . $arOneOffer['ID'] ?>"
                                         style="<? echo $strSlideStyle; ?>"
                                         data-value="<? echo $arOneOffer['ID']; ?>"></div>
                                </div>
                            </div>
                        <?
                        }
                    }
                }
                ?>
            </div>
        </div>
        <div id="tabs">
            <div class="bx_rt_name">
                <div class="rt_name">
                    <? if ($arParams["DISPLAY_NAME"] != "Y" && $arResult["NAME"]): ?>
                        <?= $arResult["NAME"] ?>
                    <? endif; ?></div>
                <div class="rt_artic">
                    <? echo $arResult['PROPERTIES']['ARTNUMBER']['VALUE']; ?> </div>
            </div>
            <ul class="accordion-tabs">
                <li class="tab-head-cont"><a href="#tabs-1" title="">Детали</a>
                    <section>
                        <div id="tabs-1" class="bx_rt">
                            <?
                            $useBrands = ('Y' == $arParams['BRAND_USE']);
                            $useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
                            if ($useBrands || $useVoteRating) {
                                ?>
                                <div class="bx_optionblock">
                                    <?
                                    if ($useVoteRating) {
                                        ?><?$APPLICATION->IncludeComponent(
                                            "bitrix:iblock.vote",
                                            "stars",
                                            array(
                                                "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                                                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                                                "ELEMENT_ID" => $arResult['ID'],
                                                "ELEMENT_CODE" => "",
                                                "MAX_VOTE" => "5",
                                                "VOTE_NAMES" => array("1", "2", "3", "4", "5"),
                                                "SET_STATUS_404" => "N",
                                                "DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
                                                "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                                                "CACHE_TIME" => $arParams['CACHE_TIME']
                                            ),
                                            $component,
                                            array("HIDE_ICONS" => "Y")
                                        );?><?
                                    }
                                    if ($useBrands) {
                                        ?><?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", ".default", array(
                                            "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                                            "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                                            "ELEMENT_ID" => $arResult['ID'],
                                            "ELEMENT_CODE" => "",
                                            "PROP_CODE" => $arParams['BRAND_PROP_CODE'],
                                            "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                                            "CACHE_TIME" => $arParams['CACHE_TIME'],
                                            "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                                            "WIDTH" => "",
                                            "HEIGHT" => ""
                                        ),
                                            $component,
                                            array("HIDE_ICONS" => "Y")
                                        );?><?
                                    }
                                    ?>
                                </div>
                            <?
                            }
                            unset($useVoteRating);
                            unset($useBrands);
                            ?>
                            <?
                            if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS']) {
                                ?>
                                <div class="item_info_section">
                                    <?
                                    if (!empty($arResult['DISPLAY_PROPERTIES'])) {
                                        ?>
                                        <dl>
                                            <?
                                            foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp) {
                                                ?>
                                                <dt><? echo $arOneProp['NAME']; ?></dt><?
                                                echo '<dd>', (
                                                is_array($arOneProp['DISPLAY_VALUE'])
                                                    ? '<span class="item_info_section-size active">' . implode('</span> / ' . '<span class="item_info_section-size">', $arOneProp['DISPLAY_VALUE']) . '</span>'
                                                    : $arOneProp['DISPLAY_VALUE']
                                                ), '</dd>';
                                            }
                                            unset($arOneProp);
                                            ?>
                                        </dl>
                                    <?
                                    }
                                    if ($arResult['SHOW_OFFERS_PROPS']) {
                                        ?>
                                        <dl id="<? echo $arItemIDs['DISPLAY_PROP_DIV'] ?>" style="display: none;"></dl>
                                    <?
                                    }
                                    ?>
                                </div>
                            <?
                            }?>
                            <style type="text/css">
                            .sizeTableModalShowButton{
                               position: relative;
                               top: -14px;
                               font-size: 18px;
                               color: #a7a7a7;
                               text-decoration: underline;
                               cursor: pointer;
                               display: inline-block;
                            }

                            </style>
<div class="sizeTableModalShowButton">Определить свой размер</div>


                            <?
if ('' != $arResult['PREVIEW_TEXT'])
{
	if (
		'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
		|| ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
	)
	{
?>
<div class="item_info_section">
<?
		echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>');
?>
</div>
<?
	}
}
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP']))
{
	$arSkuProps = array();
?>
<div class="item_info_section scu_size" id="<? echo $arItemIDs['PROP_DIV']; ?>">
<?
	foreach ($arResult['SKU_PROPS'] as &$arProp)
	{
		if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
			continue;
		$arSkuProps[] = array(
			'ID' => $arProp['ID'],
			'SHOW_MODE' => $arProp['SHOW_MODE'],
			'VALUES_COUNT' => $arProp['VALUES_COUNT']
		);
		if ('TEXT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_size full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_size';
				$strOneWidth = '20%';
				$strDioneWidth = '28px';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_size_scroller_container"><div class="bx_size">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
				$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
?>
<li data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID']; ?>" data-onevalue="<? echo $arOneValue['ID']; ?>" style="width: <? echo $strDioneWidth; ?>; display: none;">
<i title="<? echo $arOneValue['NAME']; ?>"></i><span class="cnt" title="<? echo $arOneValue['NAME']; ?>"><? echo $arOneValue['NAME']; ?></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
		elseif ('PICT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_scu full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_scu';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_scu_scroller_container"><div class="bx_scu">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
				$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
?>
<li data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID'] ?>" data-onevalue="<? echo $arOneValue['ID']; ?>" style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>; display: none;" >
<i title="<? echo $arOneValue['NAME']; ?>"></i>
<span class="cnt"><span class="cnt_item" style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');" title="<? echo $arOneValue['NAME']; ?>"></span></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
	}
	unset($arProp);
?>
</div>
<?
}
?>

<div class="item_info_section" style="height:50px; width: 100%;">
<div class="item_price">
<?
$boolDiscountShow = (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']);
?>
	<div class="item_old_price" id="<? echo $arItemIDs['OLD_PRICE']; ?>" style="display: <? echo ($boolDiscountShow ? '' : 'none'); ?>"><? echo ($boolDiscountShow ? $arResult['MIN_PRICE']['PRINT_VALUE'] : ''); ?></div>
	<div class="item_current_price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo $arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?></div>
	<div class="item_economy_price" id="<? echo $arItemIDs['DISCOUNT_PRICE']; ?>" style="display: <? echo ($boolDiscountShow ? '' : 'none'); ?>"><? echo ($boolDiscountShow ? GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arResult['MIN_PRICE']['PRINT_DISCOUNT_DIFF'])) : ''); ?></div>
</div>
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	$canBuy = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
}
else
{
	$canBuy = $arResult['CAN_BUY'];
}
if ($canBuy)
{
	$buyBtnMessage = ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
	$buyBtnClass = 'bx_big bx_bt_button bx_cart';
}
else
{
	$buyBtnMessage = ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
	$buyBtnClass = 'bx_big bx_bt_button_type_2 bx_cart';
}
if ('Y' == $arParams['USE_PRODUCT_QUANTITY'])
{
?>
	<div class="item_buttons vam">
		<span class="item_buttons_counter_block" style="display:none;">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>">-</a>
			<input id="<? echo $arItemIDs['QUANTITY']; ?>" type="text" class="tac transparent_input" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
					? 1
					: $arResult['CATALOG_MEASURE_RATIO']
				); ?>">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<? echo $arItemIDs['QUANTITY_UP']; ?>">+</a>
			<span class="bx_cnt_desc" id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><? echo (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : ''); ?></span>
		</span>

			<a href="javascript:void(0);" class="<? echo $buyBtnClass; ?>" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
<?
	if ('Y' == $arParams['DISPLAY_COMPARE'])
	{
?>
			<a href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" style="margin-left: 10px"><? echo ('' != $arParams['MESS_BTN_COMPARE']
					? $arParams['MESS_BTN_COMPARE']
					: GetMessage('CT_BCE_CATALOG_COMPARE')
				); ?></a>
<?
	}
?>

	</div>
<?
	if ('Y' == $arParams['SHOW_MAX_QUANTITY'])
	{
		if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
		{
?>
	<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>" style="display: none;"><? echo GetMessage('OSTATOK'); ?>: <span></span></p>
<?
		}
		else
		{
			if ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO'])
			{
?>
	<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>"><? echo GetMessage('OSTATOK'); ?>: <span><? echo $arResult['CATALOG_QUANTITY']; ?></span></p>
<?
			}
		}
	}
}
else
{
?>
	<div class="item_buttons vam">
		<span class="item_buttons_counter_block">
			<a href="javascript:void(0);" class="<? echo $buyBtnClass; ?>" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
<?
	if ('Y' == $arParams['DISPLAY_COMPARE'])
	{
?>
			<a id="<? echo $arItemIDs['COMPARE_LINK']; ?>" href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" style="margin-left: 10px"><? echo ('' != $arParams['MESS_BTN_COMPARE']
					? $arParams['MESS_BTN_COMPARE']
					: GetMessage('CT_BCE_CATALOG_COMPARE')
				); ?></a>
<?
	}
?>
		</span>
	</div>
<?
}
?>
</div>
<div class="dostavka_text">
<?$APPLICATION->IncludeComponent(
      "bitrix:main.include", "",
     array("AREA_FILE_SHOW" => "file",
           "PATH" => SITE_DIR."/include/dostavka.php"),
    false
);?> </div>
			<div class="clb"></div>
		</div>
	</section>
	</li>
        <li class="tab-head-cont"><a href="#tabs-2" title="">Описание и уход</a>
		<section style="margin-top: -1px;">
						<div id="tabs-2" class="bx_rb">
<div class="item_info_section">
<?
if ('' != $arResult['DETAIL_TEXT'])
{
?>
	<div class="bx_item_description">
		<div class="bx_item_section_name_gray" style="border-bottom: 1px solid #f2f2f2;"><? echo GetMessage('FULL_DESCRIPTION'); ?></div>
<?
	if ('html' == $arResult['DETAIL_TEXT_TYPE'])
	{
		echo $arResult['DETAIL_TEXT'];
	}
	else
	{
		?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
	}
?>
	</div>
<?
}
?>
</div>
		</div>
		</section>
		</li>
    </ul>
</div>
		<div class="bx_md">
<div class="item_info_section">
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	if ($arResult['OFFER_GROUP'])
	{
		foreach ($arResult['OFFERS'] as $arOffer)
		{
			if (!$arOffer['OFFER_GROUP'])
				continue;
?>

<?
		}
	}
}
else
{
	if ($arResult['MODULES']['catalog'])
	{
?><?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	".default",
	array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $arResult["ID"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
		"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"]
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?><?
	}
}
?>
</div>
		</div>

		<div class="bx_lb">
<div class="tac ovh">
</div>
<div class="tab-section-container">
<?
if ('Y' == $arParams['USE_COMMENTS'])
{
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.comments",
	"",
	array(
		"ELEMENT_ID" => $arResult['ID'],
		"ELEMENT_CODE" => "",
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"URL_TO_COMMENT" => "",
		"WIDTH" => "",
		"COMMENTS_COUNT" => "5",
		"BLOG_USE" => $arParams['BLOG_USE'],
		"FB_USE" => $arParams['FB_USE'],
		"FB_APP_ID" => $arParams['FB_APP_ID'],
		"VK_USE" => $arParams['VK_USE'],
		"VK_API_ID" => $arParams['VK_API_ID'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"BLOG_TITLE" => "",
		"BLOG_URL" => $arParams['BLOG_URL'],
		"PATH_TO_SMILE" => "",
		"EMAIL_NOTIFY" => $arParams['BLOG_EMAIL_NOTIFY'],
		"AJAX_POST" => "Y",
		"SHOW_SPAM" => "Y",
		"SHOW_RATING" => "N",
		"FB_TITLE" => "",
		"FB_USER_ADMIN_ID" => "",
		"FB_COLORSCHEME" => "light",
		"FB_ORDER_BY" => "reverse_time",
		"VK_TITLE" => "",
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME']
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?>
<?
}
?>
</div>
		</div>
			<div style="clear: both;"></div>
	</div>
	<div class="clb"></div>
</div><?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	foreach ($arResult['JS_OFFERS'] as &$arOneJS)
	{
		if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE'])
		{
			$arOneJS['PRICE']['PRINT_DISCOUNT_DIFF'] = GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arOneJS['PRICE']['PRINT_DISCOUNT_DIFF']));
			$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
		}
		$strProps = '';
		if ($arResult['SHOW_OFFERS_PROPS'])
		{
			if (!empty($arOneJS['DISPLAY_PROPERTIES']))
			{
				foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp)
				{
					$strProps .= '<dt>'.$arOneProp['NAME'].'</dt><dd>'.(
						is_array($arOneProp['VALUE'])
						? implode(' / ', $arOneProp['VALUE'])
						: $arOneProp['VALUE']
					).'</dd>';
				}
			}
		}
		$arOneJS['DISPLAY_PROPERTIES'] = $strProps;
	}
	if (isset($arOneJS))
		unset($arOneJS);
	$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => true,
			'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
			'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
			'DISPLAY_COMPARE' => ('Y' == $arParams['DISPLAY_COMPARE']),
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE']
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'NAME' => $arResult['~NAME']
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $arSkuProps
	);
}
else
{
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
	{
?>
<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
<?
		if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
		{
			foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
			{
?>
	<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
<?
				if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
					unset($arResult['PRODUCT_PROPERTIES'][$propID]);
			}
		}
		$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
		if (!$emptyProductProperties)
		{
?>
	<table>
<?
			foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo)
			{
?>
	<tr><td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
	<td>
<?
				if(
					'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
					&& 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
				)
				{
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><label><input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?></label><br><?
					}
				}
				else
				{
					?><select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option><?
					}
					?></select><?
				}
?>
	</td></tr>
<?
			}
?>
	</table>
<?
		}
?>
</div>
<?
	}
	$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
			'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
			'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
			'DISPLAY_COMPARE' => ('Y' == $arParams['DISPLAY_COMPARE']),
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE']
		),
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'PICT' => $arFirstPhoto,
			'NAME' => $arResult['~NAME'],
			'SUBSCRIPTION' => true,
			'PRICE' => $arResult['MIN_PRICE'],
			'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER' => $arResult['MORE_PHOTO'],
			'CAN_BUY' => $arResult['CAN_BUY'],
			'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
			'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
			'BUY_URL' => $arResult['~BUY_URL'],
		),
		'BASKET' => array(
			'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $emptyProductProperties,
			'BASKET_URL' => $arParams['BASKET_URL']
		)
	);
	unset($emptyProductProperties);
}
?>

<script>
    window.addEventListener("load", function () {
        var modalButtons = document.getElementsByClassName("sizeTableModalShowButton");
        var blackScreen = document.getElementsByClassName("blackScreen")[0];
        var modalTabs = document.getElementsByClassName("tabs2")[0];
        blackScreen.addEventListener("click", function (ev) {
            ev.preventDefault();
            this.className += " invisible";
            modalTabs.className += " invisible";
        });
        var closeButton = document.getElementsByClassName("modalCloseButton")[0];
        closeButton.addEventListener("click", function (ev) {
            ev.preventDefault();
            blackScreen.className += " invisible";
            modalTabs.className += " invisible";
        });

        for (var i = 0; i < modalButtons.length; i++) {
            modalButtons[i].addEventListener("click", function (ev) {
                ev.preventDefault();
                blackScreen.className = blackScreen.className.match(/[0-9A-Za-z\-_]+/);
                modalTabs.className = modalTabs.className.match(/[0-9A-Za-z\-_]+/);
            });
        }
    });
</script>

<div class="blackScreen invisible"></div>
<div class="tabs2 invisible">
    <div class="closeModal"><span class="modalCloseButton">&times;</span></div>

    <ul class="sizeTableTabs">
        <li class="tab-head-cont"><a href="#tabs-3" title="" class="is-active">ЛЕГГИНСЫ/БРЮКИ</a>
            <section class="is-open" style="display: block;">
                TEST1
            </section>
        </li>
        <li class="tab-head-cont"><a href="#tabs-4" style="margin: 0 -4px; width:249px !important;" title="">ФУТБОЛКИ/МАЙКИ/ТОПЫ</a>
            <section style="height: auto !important">

                <div class="verticalTextAndImage">
                    <span class="verticalTextInTable">ЖЕНЩИНЫ</span>
                    <img class="sizeTableImageNearVerticalText" src="<?=SITE_TEMPLATE_PATH?>/image/leftImageInSizeTable.jpg" alt=""/>
                </div>
                <table class="sizeTable2">
                    <tr class="f500">
                        <td>Длина</td>
                        <td>XS</td>
                        <td>S</td>
                        <td>M</td>
                        <td>L</td>
                        <td>XL</td>
                    </tr>
                    <tr>
                        <td class="f500">A</td>
                        <td>36</td>
                        <td>38</td>
                        <td>40</td>
                        <td>42</td>
                        <td>44</td>
                    </tr>
                    <tr>
                        <td class="f500">B</td>
                        <td>56</td>
                        <td>57</td>
                        <td>58</td>
                        <td>60</td>
                        <td>62</td>
                    </tr>
                    <tr class="f500">
                        <td><img class="flagRus2" src="<?= SITE_TEMPLATE_PATH ?>/image/flagRus.jpg" alt=""/></td>
                        <td>40&nbsp;&dash;&nbsp;42</td>
                        <td>42&nbsp;&dash;&nbsp;44</td>
                        <td>44&nbsp;&dash;&nbsp;46</td>
                        <td>46&nbsp;&dash;&nbsp;48</td>
                        <td>48&nbsp;&dash;&nbsp;50</td>
                    </tr>

                </table>
                <div class="line"></div>

                <div class="verticalTextAndImage2">
                    <span class="verticalTextInTable2">МУЖЧИНЫ</span>
                    <img class="sizeTableImageNearVerticalText2" src="<?=SITE_TEMPLATE_PATH?>/image/leftImageInSizeTable2.png" alt=""/>
                </div>
                <table class="sizeTable2 mt20">
                    <tr class="f500">
                        <td>Длина</td>
                        <td>S</td>
                        <td>M</td>
                        <td>L</td>
                        <td>XL</td>
                        <td>XXL</td>
                    </tr>
                    <tr>
                        <td class="f500">A</td>
                        <td>46</td>
                        <td>48</td>
                        <td>50</td>
                        <td>52</td>
                        <td>54</td>
                    </tr>
                    <tr>
                        <td class="f500">B</td>
                        <td>65</td>
                        <td>66</td>
                        <td>67</td>
                        <td>68</td>
                        <td>72</td>
                    </tr>
                    <tr class="f500">
                        <td><img class="flagRus2" src="<?= SITE_TEMPLATE_PATH ?>/image/flagRus.jpg" alt=""/></td>
                        <td>42&nbsp;&dash;&nbsp;44</td>
                        <td>44&nbsp;&dash;&nbsp;46</td>
                        <td>46&nbsp;&dash;&nbsp;48</td>
                        <td>48&nbsp;&dash;&nbsp;50</td>
                        <td>50&nbsp;&dash;&nbsp;52</td>
                    </tr>

                </table>
                <div class="line"></div>

                <div class="verticalTextAndImage2">
                    <span class="verticalTextInTable3">ДЕТИ</span>
                    <img class="sizeTableImageNearVerticalText3" src="<?=SITE_TEMPLATE_PATH?>/image/leftImageInSizeTable3.jpg" alt=""/>
                </div>
                <table class="sizeTable2 mt20 wider">
                    <tr class="f500">
                        <td><img class="flagRus2" src="<?= SITE_TEMPLATE_PATH ?>/image/flagRus.jpg" alt=""/></td>
                        <td>52</td>
                        <td>56</td>
                        <td>60</td>
                        <td>64</td>
                        <td>68</td>
                        <td>72</td>
                    </tr>
                    <tr>
                        <td class="f500">A</td>
                        <td>26</td>
                        <td>28</td>
                        <td>30</td>
                        <td>32</td>
                        <td>34</td>
                        <td>36</td>
                    </tr>
                    <tr>
                        <td class="f500">B</td>
                        <td>39</td>
                        <td>42</td>
                        <td>45</td>
                        <td>48</td>
                        <td>51</td>
                        <td>54</td>
                    </tr>
                    <tr class="f500">
                        <td>Возраст</td>
                        <td>1&nbsp;&dash;&nbsp;2</td>
                        <td>3&nbsp;&dash;&nbsp;4</td>
                        <td>4&nbsp;&dash;&nbsp;5</td>
                        <td>6&nbsp;&dash;&nbsp;7</td>
                        <td>7&nbsp;&dash;&nbsp;8</td>
                        <td>9&nbsp;&dash;&nbsp;10</td>
                    </tr>

                </table>
                <div class="line"></div>

                <div class="whiteOverSection higher">

                <span class="sizeFooterSpan" style="font-size: 15.6px">
                    Для футболок, маек и топов основной параметр &ndash;
                    обхват груди. В размерной таблице в можете сопоставить
                    свои размеры с данными<br/>
                    и подобрать соответствующий размер.
                    <br/>
                    <br/>
                    А &ndash; сантиметровая лента проходит по наиболее
                    выступающим точкам груди, далее - под подмышечными
                    впадинами, на спине - немного выше.<br/>
                    B &ndash; длина по спинке - измеряется от седьмого
                    шейного позвонка вдоль линии середины спины до низа
                    изделия.
                    <br/>
                    <br/>
                    <b>Полученные мерки делятся пополам и в соответствии
                    с таблицей выбирается подходящий размер. </b>



                </span>
                </div>

            </section>
        </li>
        <li class="tab-head-cont"><a href="#tabs-5" title="" class="">СПОРТИВНЫЕ КОСТЮМЫ</a>
            <section >
                <table class="sizeTable">
                    <caption>ЖЕНЩИНЫ</caption>
                    <tr class="f500">
                        <td><span class="sizeTableSpan">Размер</span><img
                                src="<?= SITE_TEMPLATE_PATH ?>/image/flagRus.jpg" alt=""/></td>
                        <td>XS&nbsp;|&nbsp;42</td>
                        <td>S&nbsp;|&nbsp;44</td>
                        <td>M&nbsp;|&nbsp;46</td>
                        <td>L&nbsp;|&nbsp;48</td>
                        <td>XL&nbsp;|&nbsp;50</td>
                    </tr>
                    <tr>
                        <td class="f500">Объем груди</td>
                        <td>83</td>
                        <td>87</td>
                        <td>92</td>
                        <td>97</td>
                        <td>102</td>
                    </tr>
                    <tr>
                        <td class="f500">Объем талии</td>
                        <td>64</td>
                        <td>68</td>
                        <td>72</td>
                        <td>76</td>
                        <td>81</td>
                    </tr>
                    <tr>
                        <td class="f500">Объем бедер</td>
                        <td>88</td>
                        <td>92</td>
                        <td>97</td>
                        <td>102</td>
                        <td>107</td>
                    </tr>

                </table>
                <div class="line"></div>
                <table class="sizeTable secondTable">
                    <caption>МУЖЧИНЫ</caption>
                    <tr class="f500">
                        <td><span class="sizeTableSpan">Размер</span><img
                                src="<?= SITE_TEMPLATE_PATH ?>/image/flagRus.jpg" alt=""/></td>
                        <td>S&nbsp;|&nbsp;44</td>
                        <td>M&nbsp;|&nbsp;46</td>
                        <td>L&nbsp;|&nbsp;48</td>
                        <td>XL&nbsp;|&nbsp;50</td>
                        <td>XXL&nbsp;|&nbsp;52</td>
                    </tr>
                    <tr>
                        <td class="f500">Объем груди</td>
                        <td>88</td>
                        <td>92</td>
                        <td>96</td>
                        <td>100</td>
                        <td>102</td>
                    </tr>
                    <tr>
                        <td class="f500">Объем талии</td>
                        <td>70</td>
                        <td>82</td>
                        <td>86</td>
                        <td>90</td>
                        <td>92</td>
                    </tr>
                    <tr>
                        <td class="f500">Объем бедер</td>
                        <td>90</td>
                        <td>94</td>
                        <td>98</td>
                        <td>102</td>
                        <td>104</td>
                    </tr>

                </table>
                <div class="line"></div>
                <div class="whiteOverSection">

                <span class="sizeFooterSpan">
                    Обхват груди &ndash; сантиметровая лента проходит по
                    наиболее выступающим точкам груди, далее - под
                    подмышечными впадинами,<br/>
                    на спине - немного выше. <br/>
                    Обхват талии &ndash; измеряется строго по линии талии. <br/>
                    Обхват бедер &ndash; сантиметровая лента проходит по
                    наиболее выступающим точкам ягодиц.
                </span>
                </div>
            </section>
        </li>
    </ul>
</div>
<script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
BX.message({
	MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CT_BCE_CATALOG_BUY')); ?>',
	MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CT_BCE_CATALOG_ADD')); ?>',
	MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? CUtil::JSEscape($arParams['MESS_NOT_AVAILABLE']) : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE')); ?>',
	TITLE_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
	TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
	BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
	BTN_SEND_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
	BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE') ?>',
	SITE_ID: '<? echo SITE_ID; ?>'
});
</script>