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
                                                style="width: <? echo $strNewWidth; ?>; height: <? echo $strNewHeight; ?>;">
                                                <span class="cnt"><span class="cnt_item"
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
                                        ); ?><?
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
                                        ); ?><?
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
                            } ?>
                            <style type="text/css">
                                .sizeTableModalShowButton {
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
                            if ('' != $arResult['PREVIEW_TEXT']) {
                                if (
                                    'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
                                    || ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
                                ) {
                                    ?>
                                    <div class="item_info_section">
                                        <?
                                        echo('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>' . $arResult['PREVIEW_TEXT'] . '</p>');
                                        ?>
                                    </div>
                                <?
                                }
                            }
                            if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP'])) {
                                $arSkuProps = array();
                                ?>
                                <div class="item_info_section scu_size" id="<? echo $arItemIDs['PROP_DIV']; ?>">
                                    <?
                                    foreach ($arResult['SKU_PROPS'] as &$arProp) {
                                        if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
                                            continue;
                                        $arSkuProps[] = array(
                                            'ID' => $arProp['ID'],
                                            'SHOW_MODE' => $arProp['SHOW_MODE'],
                                            'VALUES_COUNT' => $arProp['VALUES_COUNT']
                                        );
                                        if ('TEXT' == $arProp['SHOW_MODE']) {
                                            if (5 < $arProp['VALUES_COUNT']) {
                                                $strClass = 'bx_item_detail_size full';
                                                $strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
                                                $strWidth = (20 * $arProp['VALUES_COUNT']) . '%';
                                                $strSlideStyle = '';
                                            } else {
                                                $strClass = 'bx_item_detail_size';
                                                $strOneWidth = '20%';
                                                $strDioneWidth = '28px';
                                                $strWidth = '100%';
                                                $strSlideStyle = 'display: none;';
                                            }
                                            ?>
                                            <div class="<? echo $strClass; ?>"
                                                 id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_cont">
                                                <span
                                                    class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>

                                                <div class="bx_size_scroller_container">
                                                    <div class="bx_size">
                                                        <ul id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list"
                                                            style="width: <? echo $strWidth; ?>;margin-left:0%;">
                                                            <?
                                                            foreach ($arProp['VALUES'] as $arOneValue) {
                                                                $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
                                                                ?>
                                                                <li data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID']; ?>"
                                                                    data-onevalue="<? echo $arOneValue['ID']; ?>"
                                                                    style="width: <? echo $strDioneWidth; ?>; display: none;">
                                                                    <i title="<? echo $arOneValue['NAME']; ?>"></i><span
                                                                        class="cnt"
                                                                        title="<? echo $arOneValue['NAME']; ?>"><? echo $arOneValue['NAME']; ?></span>
                                                                </li>
                                                            <?
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <div class="bx_slide_left" style="<? echo $strSlideStyle; ?>"
                                                         id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_left"
                                                         data-treevalue="<? echo $arProp['ID']; ?>"></div>
                                                    <div class="bx_slide_right" style="<? echo $strSlideStyle; ?>"
                                                         id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_right"
                                                         data-treevalue="<? echo $arProp['ID']; ?>"></div>
                                                </div>
                                            </div>
                                        <?
                                        } elseif ('PICT' == $arProp['SHOW_MODE']) {
                                            if (5 < $arProp['VALUES_COUNT']) {
                                                $strClass = 'bx_item_detail_scu full';
                                                $strOneWidth = (100 / $arProp['VALUES_COUNT']) . '%';
                                                $strWidth = (20 * $arProp['VALUES_COUNT']) . '%';
                                                $strSlideStyle = '';
                                            } else {
                                                $strClass = 'bx_item_detail_scu';
                                                $strOneWidth = '20%';
                                                $strWidth = '100%';
                                                $strSlideStyle = 'display: none;';
                                            }
                                            ?>
                                            <div class="<? echo $strClass; ?>"
                                                 id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_cont">
                                                <span
                                                    class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>

                                                <div class="bx_scu_scroller_container">
                                                    <div class="bx_scu">
                                                        <ul id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list"
                                                            style="width: <? echo $strWidth; ?>;margin-left:0%;">
                                                            <?
                                                            foreach ($arProp['VALUES'] as $arOneValue) {
                                                                $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
                                                                ?>
                                                                <li data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID'] ?>"
                                                                    data-onevalue="<? echo $arOneValue['ID']; ?>"
                                                                    style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>; display: none;">
                                                                    <i title="<? echo $arOneValue['NAME']; ?>"></i>
                                                                    <span class="cnt"><span class="cnt_item"
                                                                                            style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
                                                                                            title="<? echo $arOneValue['NAME']; ?>"></span></span>
                                                                </li>
                                                            <?
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <div class="bx_slide_left" style="<? echo $strSlideStyle; ?>"
                                                         id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_left"
                                                         data-treevalue="<? echo $arProp['ID']; ?>"></div>
                                                    <div class="bx_slide_right" style="<? echo $strSlideStyle; ?>"
                                                         id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_right"
                                                         data-treevalue="<? echo $arProp['ID']; ?>"></div>
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
                                    <div class="item_old_price" id="<? echo $arItemIDs['OLD_PRICE']; ?>"
                                         style="display: <? echo($boolDiscountShow ? '' : 'none'); ?>"><? echo($boolDiscountShow ? $arResult['MIN_PRICE']['PRINT_VALUE'] : ''); ?></div>
                                    <div class="item_current_price"
                                         id="<? echo $arItemIDs['PRICE']; ?>"><? echo $arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?></div>
                                    <div class="item_economy_price" id="<? echo $arItemIDs['DISCOUNT_PRICE']; ?>"
                                         style="display: <? echo($boolDiscountShow ? '' : 'none'); ?>"><? echo($boolDiscountShow ? GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arResult['MIN_PRICE']['PRINT_DISCOUNT_DIFF'])) : ''); ?></div>
                                </div>
                                <?
                                if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
                                    $canBuy = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
                                } else {
                                    $canBuy = $arResult['CAN_BUY'];
                                }
                                if ($canBuy) {
                                    $buyBtnMessage = ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
                                    $buyBtnClass = 'bx_big bx_bt_button bx_cart';
                                } else {
                                    $buyBtnMessage = ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
                                    $buyBtnClass = 'bx_big bx_bt_button_type_2 bx_cart';
                                }
                                if ('Y' == $arParams['USE_PRODUCT_QUANTITY']) {
                                    ?>
                                    <div class="item_buttons vam">
		<span class="item_buttons_counter_block" style="display:none;">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb"
               id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>">-</a>
			<input id="<? echo $arItemIDs['QUANTITY']; ?>" type="text" class="tac transparent_input"
                   value="<? echo(isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
                       ? 1
                       : $arResult['CATALOG_MEASURE_RATIO']
                   ); ?>">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb"
               id="<? echo $arItemIDs['QUANTITY_UP']; ?>">+</a>
			<span class="bx_cnt_desc"
                  id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><? echo(isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : ''); ?></span>
		</span>

                                        <a href="javascript:void(0);" class="<? echo $buyBtnClass; ?>"
                                           id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?>
                                        </a>
                                        <?
                                        if ('Y' == $arParams['DISPLAY_COMPARE']) {
                                            ?>
                                            <a href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart"
                                               style="margin-left: 10px"><? echo('' != $arParams['MESS_BTN_COMPARE']
                                                    ? $arParams['MESS_BTN_COMPARE']
                                                    : GetMessage('CT_BCE_CATALOG_COMPARE')
                                                ); ?></a>
                                        <?
                                        }
                                        ?>

                                    </div>
                                    <?
                                    if ('Y' == $arParams['SHOW_MAX_QUANTITY']) {
                                        if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
                                            ?>
                                            <p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>"
                                               style="display: none;"><? echo GetMessage('OSTATOK'); ?>: <span></span>
                                            </p>
                                        <?
                                        } else {
                                            if ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']) {
                                                ?>
                                                <p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>"><? echo GetMessage('OSTATOK'); ?>
                                                    : <span><? echo $arResult['CATALOG_QUANTITY']; ?></span></p>
                                            <?
                                            }
                                        }
                                    }
                                } else {
                                    ?>
                                    <div class="item_buttons vam">
		<span class="item_buttons_counter_block">
			<a href="javascript:void(0);" class="<? echo $buyBtnClass; ?>"
               id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
            <?
            if ('Y' == $arParams['DISPLAY_COMPARE']) {
                ?>
                <a id="<? echo $arItemIDs['COMPARE_LINK']; ?>" href="javascript:void(0)"
                   class="bx_big bx_bt_button_type_2 bx_cart"
                   style="margin-left: 10px"><? echo('' != $arParams['MESS_BTN_COMPARE']
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
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:main.include", "",
                                    array("AREA_FILE_SHOW" => "file",
                                        "PATH" => SITE_DIR . "/include/dostavka.php"),
                                    false
                                ); ?> </div>
                            <div class="clb"></div>
                        </div>
                    </section>
                </li>
                <li class="tab-head-cont"><a href="#tabs-2" title="">Описание и уход</a>
                    <section style="margin-top: -1px;">
                        <div id="tabs-2" class="bx_rb">
                            <div class="item_info_section">
                                <?
                                if ('' != $arResult['DETAIL_TEXT']) {
                                    ?>
                                    <div class="bx_item_description">
                                        <div class="bx_item_section_name_gray"
                                             style="border-bottom: 1px solid #f2f2f2;"><? echo GetMessage('FULL_DESCRIPTION'); ?></div>
                                        <?
                                        if ('html' == $arResult['DETAIL_TEXT_TYPE']) {
                                            echo $arResult['DETAIL_TEXT'];
                                        } else {
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
                if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
                    if ($arResult['OFFER_GROUP']) {
                        foreach ($arResult['OFFERS'] as $arOffer) {
                            if (!$arOffer['OFFER_GROUP'])
                                continue;
                            ?>

                        <?
                        }
                    }
                } else {
                    if ($arResult['MODULES']['catalog']) {
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
                        ); ?><?
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
                if ('Y' == $arParams['USE_COMMENTS']) {
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
                    ); ?>
                <?
                }
                ?>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="clb"></div>
</div><?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
    foreach ($arResult['JS_OFFERS'] as &$arOneJS) {
        if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE']) {
            $arOneJS['PRICE']['PRINT_DISCOUNT_DIFF'] = GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arOneJS['PRICE']['PRINT_DISCOUNT_DIFF']));
            $arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
        }
        $strProps = '';
        if ($arResult['SHOW_OFFERS_PROPS']) {
            if (!empty($arOneJS['DISPLAY_PROPERTIES'])) {
                foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp) {
                    $strProps .= '<dt>' . $arOneProp['NAME'] . '</dt><dd>' . (
                        is_array($arOneProp['VALUE'])
                            ? implode(' / ', $arOneProp['VALUE'])
                            : $arOneProp['VALUE']
                        ) . '</dd>';
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
} else {
    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
    if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties) {
        ?>
        <div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
            <?
            if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
                foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
                    ?>
                    <input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                           value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
                    <?
                    if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
                        unset($arResult['PRODUCT_PROPERTIES'][$propID]);
                }
            }
            $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
            if (!$emptyProductProperties) {
                ?>
                <table>
                    <?
                    foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo) {
                        ?>
                        <tr>
                            <td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
                            <td>
                                <?
                                if (
                                    'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
                                    && 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
                                ) {
                                    foreach ($propInfo['VALUES'] as $valueID => $value) {
                                        ?><label><input type="radio"
                                                        name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                                                        value="<? echo $valueID; ?>" <? echo($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
                                        </label><br><?
                                    }
                                } else {
                                    ?><select
                                    name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
                                    foreach ($propInfo['VALUES'] as $valueID => $value) {
                                        ?>
                                        <option
                                        value="<? echo $valueID; ?>" <? echo($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option><?
                                    }
                                    ?></select><?
                                }
                                ?>
                            </td>
                        </tr>
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
                    <img class="sizeTableImageNearVerticalText"
                         src="<?= SITE_TEMPLATE_PATH ?>/image/leftImageInSizeTable.jpg" alt=""/>
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
                    <img class="sizeTableImageNearVerticalText2"
                         src="<?= SITE_TEMPLATE_PATH ?>/image/leftImageInSizeTable2.png" alt=""/>
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
                    <img class="sizeTableImageNearVerticalText3"
                         src="<?= SITE_TEMPLATE_PATH ?>/image/leftImageInSizeTable3.jpg" alt=""/>
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
            <section>
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


<div id="ofertaTrigger">OFERTA</div>
<div id="blackBackground" class="blackBackground invisible"></div>
<div id="ofertaModalWindow" class="ofertaModalWindow invisible">
    <div class="ofertaModalClose"><span class="ofertaModalCloseButton">&times;</span></div>
    <div class="ofertaText">
        <h1>ПУБЛИЧНАЯ ОФЕРТА О ПРЕДОСТАВЛЕНИИ УСЛУГ</h1>

        <h2>Основные понятия</h2>
        <br/>
        <br/>

        <div class="ofertaMainText">
            <span class="bolder">Посетитель Сайта</span> — лицо, пришедшее на сайт http://www.citystarwear.com без цели
            размещения Заказа.<br/>
            <span class="bolder">Пользователь</span> — физическое лицо, посетитель Сайта, принимающий условия настоящего
            Соглашения и желающий
            разместить Заказы в Интернет-магазине Citystarwear.com<br/>
            <span class="bolder">Покупатель</span> — Пользователь, разместивший Заказ в Интернет-магазине
            Citystarwear.com<br/>
            <span class="bolder">Продавец</span> — Общество с ограниченной ответственностью «Трейд Инвест» (ОГРН
            5107746007628, ИНН 7705935687, КПП
            772601001, место нахождения: 117105, г. Казань, ул. Карима Тинчурина д. 31).<br/>
            <span class="bolder">Интернет-магазин</span> — Интернет-сайт, принадлежащий Продавцу, расположенный в сети
            интернет по адресу
            http://www.citystarwear.com , где представлены Товары, предлагаемые Продавцом для приобретения, а также
            условия
            оплаты и доставки Товаров Покупателям.<br/>
            <span class="bolder">Сайт</span> — http://www.citystarwear.com<br/>
            <span class="bolder">Товар</span> — обувь, одежда, аксессуары и иные товары, представленные к продаже на
            Сайте Продавца.<br/>
            <span class="bolder">Заказ</span> — должным образом оформленный запрос Покупателя на приобретение и доставку
            по указанному Покупателем
            адресу / посредством самовывоза Товаров, выбранных на Сайте.<br/>
            <br/>
            <br/>
            <h2>1. Общие положения</h2><br/>
            1.1. Продавец осуществляет продажу Товаров через Интернет-магазин по адресу http://www.lamoda.ru или же
            через
            мобильные приложения Lamoda для операционных систем iOS и Android.<br/>
            1.2. Заказывая Товары через Интернет-магазин, Пользователь соглашается с условиями продажи Товаров,
            изложенными
            ниже (далее — Условия продажи товаров). В случае несогласия с настоящим Пользовательским соглашением (далее
            —
            Соглашение / Публичная оферта) Пользователь обязан немедленно прекратить использование сервиса и покинуть
            сайт
            http://www.lamoda.ru.<br/>
            1.3. Настоящие Условия продажи товаров, а также информация о Товаре, представленная на Сайте, являются
            публичной
            офертой в соответствии со ст.435 и п.2 ст.437 Гражданского кодекса Российской Федерации.<br/>
            1.4. Соглашение может быть изменено Продавцом в одностороннем порядке без уведомления
            Пользователя/Покупателя.
            Новая редакция Соглашения вступает в силу по истечении 10 (Десяти) календарных дней с момента ее
            опубликования
            на Сайте, если иное не предусмотрено условиями настоящего Соглашения.<br/>
            1.5. Публичная оферта признается акцептованной Посетителем Сайта / Покупателем с момента регистрации
            Посетителя
            на Сайте, оформления Покупателем Заказа без авторизации на Сайте, через мобильные приложения Lamoda для
            операционных систем iOS и Android, а также с момента принятия от Покупателя Заказа по телефону 8 (495)
            785-72-82
            (для звонков из Москвы) и 8 (800) 250-08-78 (для звонков из регионов).<br/>
            Договор розничной купли-продажи считается заключенным с момента выдачи Продавцом Покупателю кассового или
            товарного чека либо иного документа, подтверждающего оплату товара.<br/>
            Сообщая Продавцу свой e-mail и номер телефона, Посетитель Сайта/Пользователь/Покупатель дает согласие на
            использование указанных средств связи Продавцом, а также третьими лицами, привлекаемыми им для целей
            выполнения
            обязательств перед Посетителями Сайта/Пользователями/Покупателями, в целях осуществления рассылок рекламного
            и
            информационного характера, содержащих информацию о скидках, предстоящих и действующих акциях и других
            мероприятиях Продавца, о передаче заказа в доставку, а также иную информацию, непосредственно связанную с
            выполнением обязательств Покупателем в рамках настоящей Публичной оферты.<br/>
            <br/>
            <h2>2. Предмет соглашения</h2><br/>
            2.1. Предметом настоящего Соглашения является предоставление возможности Пользователю приобретать для
            личных,
            семейных, домашних и иных нужд, не связанных с осуществлением предпринимательской деятельности, Товары,
            представленные в каталоге Интернет-магазина по адресу http://www.lamoda.ru.<br/>
            2.2. Данное Соглашение распространяется на все виды Товаров и услуг, представленных на Сайте, пока такие
            предложения с описанием присутствуют в каталоге Интернет-магазина.<br/>
            <br/>
            <h2>3. Регистрация на сайте</h2><br/>
            3.1. Регистрация на Сайте осуществляется с помощью всплывающего окна «Регистрация».<br/>
            3.2. Регистрация на Сайте не является обязательной для оформления Заказа.<br/>
            3.3. Продавец не несет ответственности за точность и правильность информации, предоставляемой Пользователем
            при
            регистрации.<br/>
            3.4. Пользователь обязуется не сообщать третьим лицам логин и пароль, указанные Пользователем при
            регистрации. В
            случае возникновения у Пользователя подозрений относительно безопасности его логина и пароля или возможности
            их
            несанкционированного использования третьими лицами, Пользователь обязуется незамедлительно уведомить об этом
            Продавца, направив соответствующее электронное письмо по адресу: help@lamoda.ru.<br/>
            3.5. Общение Пользователя/Покупателя с операторами Call-центра / менеджерами и иными представителями
            Продавца
            должно строиться на принципах общепринятой морали и коммуникационного этикета. Строго запрещено
            использование
            нецензурных слов, брани, оскорбительных выражений, а также угроз и шантажа, в независимости от того, в каком
            виде и кому они были адресованы.<br/>
            <br/>
            <h2>4. Товар и порядок совершения покупки</h2><br/>
            4.1. Продавец обеспечивает наличие на своем складе Товаров, представленных на Сайте. Сопровождающие Товар
            фотографии являются простыми иллюстрациями к нему и могут отличаться от фактического внешнего вида Товара.
            Сопровождающие Товар описания/характеристики не претендуют на исчерпывающую информативность и могут
            содержать
            опечатки. Для уточнения информации по Товару Покупатель должен обратиться в Службу поддержки клиентов.
            Обновление информации, представленной на Сайте, производится каждые 30 минут.<br/>
            4.2. В случае отсутствия заказанных Покупателем Товаров на складе Продавца, последний вправе исключить
            указанный
            Товар из Заказа / аннулировать Заказ Покупателя, уведомив об этом Покупателя путем направления
            соответствующего
            электронного сообщения по адресу, указанному Покупателем при регистрации (либо звонком оператора Call-центра
            Продавца).<br/>
            4.3. В случае аннуляции полностью либо частично предоплаченного Заказа стоимость аннулированного Товара
            возвращается Продавцом Покупателю способом, которым Товар был оплачен.<br/>
            4.4. Заказ Покупателя оформляется в соответствии с процедурами, указанными на Сайте в разделе «Оформление
            Заказа» по адресу http://www.lamoda.ru/landing/for-new-visitors/#howtoorder.<br/>
            4.5. Покупатель несет полную ответственность за предоставление неверных сведений, повлекшее за собой
            невозможность надлежащего исполнения Продавцом своих обязательств перед Покупателем.<br/>
            4.6. После оформления Заказа на Сайте Покупателю предоставляется информация о предполагаемой дате доставки
            путем
            направления электронного сообщения по адресу, указанному Покупателем при регистрации, или по телефону.
            Менеджер,
            обслуживающий данный Заказ, уточняет детали Заказа, согласовывает дату доставки, которая зависит от наличия
            заказанных Товаров на складе Продавца и времени, необходимого для обработки и доставки Заказа.<br/>
            4.7. Ожидаемая дата передачи Заказа в Службу доставки сообщается Покупателю менеджером, обслуживающим Заказ,
            по
            электронной почте или при контрольном звонке Покупателю.<br/>
            <br/>
            <h2>5. Доставка заказа</h2><br/>
            5.1. Способы, а также примерные сроки доставки Товаров указаны на Сайте в разделе «Способы доставки» по
            адресу
            http://www.lamoda.ru/about/shipping/. Конкретные сроки доставки могут быть согласованы Покупателем с
            оператором
            Call-центра при подтверждении заказа.<br/>
            5.2. Территория доставки Товаров, представленных на Сайте, ограничена пределами Российской Федерации.<br/>
            5.3. Задержки в доставке возможны ввиду непредвиденных обстоятельств, произошедших не по вине Продавца.<br/>
            5.4. При доставке Заказ вручается Покупателю либо третьему лицу, указанному в Заказе качестве получателя
            (далее
            Покупатель и третье лицо именуются «Получатель»). При невозможности получения Заказа, оплаченного
            посредством
            наличного расчета, указанными выше лицами, Заказ может быть вручен лицу, который может предоставить сведения
            о
            Заказе (номер отправления и/или ФИО Получателя), а также оплатить стоимость Заказа в полном объеме лицу,
            осуществляющему доставку Заказа.<br/>
            5.5. Во избежание случаев мошенничества, а также для выполнения взятых на себя обязательств, указанных в
            пункте
            5. настоящего Соглашения, при вручении предоплаченного Заказа лицо, осуществляющее доставку Заказа, вправе
            затребовать документ, удостоверяющий личность Получателя, а также указать тип и номер предоставленного
            Получателем документа на квитанции к Заказу. Продавец гарантирует конфиденциальность и защиту персональных
            данных Получателя (п.9.3.).<br/>
            5.6. Риск случайной гибели или случайного повреждения Товара переходит к Покупателю с момента передачи ему
            Заказа и проставления Получателем Заказа подписи в документах, подтверждающих доставку Заказа. В случае
            недоставки Заказа Продавец возмещает Покупателю стоимость предоплаченного Покупателем Заказа и доставки в
            полном
            объеме после получения от Службы доставки подтверждения утраты Заказа.<br/>
            5.7. Стоимость доставки каждого Заказа рассчитывается индивидуально, исходя из веса Товара, региона и
            способа
            доставки, а также (в случае необходимости) формы оплаты, и указывается на Сайте на последнем этапе
            оформления
            Заказа.<br/>
            5.8. Обязанность Продавца передать товар Покупателю считается исполненной в момент вручения курьером Товара
            Получателю или получения Товара Получателем в отделении почтовой связи.<br/>
            При получении Заказа в отделении почтовой связи Получатель после оплаты доставленного Товара обязан
            осмотреть
            доставленный Товар и произвести его вскрытие в присутствии работников Почты России для проверки Товара на
            соответствие заявленному количеству, ассортименту и комплектности Товара, а также проверить срок службы
            доставленного Товара и целостность упаковки. В случае наличия претензий к доставленному Товару
            (недовложение,
            вложение Товара отличного от указанного в описи отправления, производственный брак, иные претензии) по
            указанию
            Получателя работниками Почты России составляется Акт о выявленных несоответствиях. Если Получателем не были
            заявлены претензии в вышеуказанном порядке, то Продавец считается полностью и надлежащим образом исполнившим
            свою обязанность по передаче Товара.<br/>
            В случае возврата доставленного посредством Почты России Товара в связи с наличием претензий к Товару
            Получатель
            обязан приложить к Отправлению, содержащему возвращаемый Товар, следующие документы:<br/>
            заявление на возврат денежных средств;<br/>
            копию акта о выявленных несоответствиях;<br/>
            копию квитанции об оплате;<br/>
            копию описи Отправления;<br/>
            бланк возврата.<br/>
            5.9. При принятии Заказа от курьера, Получатель обязан осмотреть доставленный Товар и проверить его на
            соответствие заявленному количеству, ассортименту и комплектности Товара, а также проверить срок службы
            доставленного Товара и целостность упаковки. В случае отсутствия претензий к доставленному Товару Получатель
            расписывается в «Бланке доставки заказов» и оплачивает Заказ (в отсутствие 100%-ной предоплаты). Подпись в
            доставочных документах свидетельствует о том, что претензий к Товару Получателем не заявлено и Продавец
            полностью и надлежащим образом выполнил свою обязанность по передаче Товара.<br/>
            5.10. Время нахождения курьера по адресу Получателя ограничено 15 минутами.<br/>
            5.11. Товар, представленный на Сайте, по качеству и упаковке соответствует ГОСТу и ТУ, что подтверждается
            соответствующими документами (сертификатами и т.д.).<br/>
            5.12. Уточнить дату, время и при необходимости маршрут доставки, можно у менеджера, который связывается с
            Покупателем для подтверждения Заказа.<br/>
            5.13. Пользователь понимает и соглашается с тем, что:<br/>
            осуществление доставки — отдельная услуга, не являющаяся неотъемлемой частью приобретаемого Покупателем
            Товара,
            выполнение которой заканчивается в момент получения Получателем Товара и осуществления платежа за него.
            Претензии к качеству приобретенного Товара, возникшие после получения и оплаты Товара, рассматриваются в
            соответствии с Законом РФ «О защите прав потребителей» и гарантийными обязательствами Продавца. В связи с
            этим
            приобретение Товара с доставкой не дает Покупателю право требования доставки приобретенного Товара в целях
            гарантийного обслуживания или замены, не дает возможности осуществлять гарантийное обслуживание или замену
            Товара посредством выезда к Покупателю и не подразумевает возможность возврата стоимости доставки Товара в
            случаях, когда Покупатель имеет право на возврат денег за Товар как таковой, в соответствии с Законом РФ «О
            защите прав потребителей».<br/>
            <br/>
            <h2>6. Оплата товара</h2><br/>
            6.1. Цена товара указывается в рублях Российской Федерации и включает в себя налог на добавленную
            стоимость.<br/>
            6.2. Цена Товара указывается на Сайте. В случае неверного указания цены заказанного Покупателем Товара,
            Продавец
            информирует об этом Покупателя для подтверждения Заказа по исправленной цене либо аннулирования Заказа. При
            невозможности связаться с Покупателем данный Заказ считается аннулированным. Если Заказ был оплачен,
            Продавец
            возвращает Покупателю оплаченную за Заказ сумму тем же способом, которым она была уплачена.<br/>
            6.3. Цена Товара на Сайте может быть изменена Продавцом в одностороннем порядке. При этом цена на заказанный
            Покупателем Товар изменению не подлежит. Цена Товара может дифференцироваться по регионам.<br/>
            6.4. Особенности оплаты Товара с помощью банковских карт:<br/>
            6.4.1 В соответствии с положением ЦБ РФ «Об эмиссии банковских карт и об операциях, совершаемых с
            использованием
            платежных карт» от 24.12.2004 № 266-П операции по банковским картам совершаются держателем карты либо
            уполномоченным им лицом.<br/>
            6.4.2 Авторизация операций по банковским картам осуществляется банком. Если у банка есть основания полагать,
            что
            операция носит мошеннический характер, то банк вправе отказать в осуществлении данной операции.
            Мошеннические
            операции с банковскими картами попадают под действие статьи 159 УК РФ.<br/>
            6.4.3 Во избежание случаев различного рода неправомерного использования банковских карт при оплате все
            Заказы,
            оформленные на Сайте и предоплаченные банковской картой, проверяются Продавцом. В целях проверки личности
            владельца и его правомочности на использование карты Продавец вправе потребовать от Покупателя, оформившего
            такой заказ, предъявления документа, удостоверяющего личность.<br/>
            6.5. Продавец вправе предоставлять скидки на Товары и устанавливать программу бонусов. Виды скидок, бонусов,
            порядок и условия начисления указаны на Сайте и могут быть изменены Продавцом в одностороннем порядке.<br/>
            6.6. При проведении маркетинговых мероприятий, предполагающих вложение каких-либо объектов в отправления с
            Заказом Покупателя, доставка указанных вложений осуществляется за счет Покупателя. Для того, чтобы
            отказаться от
            вложения, Покупателю необходимо обратиться в Службу по работе с клиентами.<br/>
            6.7. При доставке товара Почтой России общая стоимость товара подлежит увеличению на стоимость доставки в
            размере, указанном в Разделе "Способы доставки по России" (http://www.lamoda.ru/about/shipping/#post).<br/>
            6.8. Продавец ведет статистику выкупленных Покупателем заказов. Товар считается неукомплектованным, если
            Покупатель сообщил об изменении решения о покупке до момента начала сборки заказа на складе. Если Покупатель
            сообщил Продавцу об этом после момента начала сборки заказа на складе, то такой товар считается отклоненным.
            Продавец вправе принять решение о блокировке для Покупателя услуги «При получении» в двух случаях.
            Во-первых,
            если в соответствии с указанной статистикой в рамках ранее оформленных подряд 3 и более заказов объем
            отклонённых Покупателем товаров составит более 70 процентов от общего объема доставленных товаров.
            Во-вторых,
            если доля неукомплектованных заказов составит более 60% от общего числа оформленных заказов и число
            неукомплектованных заказов составит не менее 5. Впоследствии Покупатель сможет вновь воспользоваться услугой
            «При получении» только после выкупа от одного до трёх заказов на общую сумму 7000 рублей в течение трех
            месяцев.<br/>
            6.9. Продавец вправе ограничивать доступные Покупателю способы оплаты в зависимости от величины заказа.
            Способ
            оплаты «При получении» не доступен для заказов, которые содержат более 40 товаров, или общая сумма заказа
            составляет более 350.000 рублей.<br/>
            <br/>
            <h2>7. Возврат товара и денежных средств</h2><br/>
            7.1. Возврат Товара осуществляется в соответствии с «Условиями возврата», указанными на Сайте по адресу
            http://www.lamoda.ru/about/return/.<br/>
            7.2. Возврат Товара надлежащего качества<br/>
            7.2.1. Покупатель вправе отказаться от заказанного Товара в любое время до его получения, а после получения
            Товара — в течение 365 дней, не считая дня покупки, за исключением Товаров, указанных в п. 7.2.4. настоящего
            Соглашения. Возврат Товара надлежащего качества возможен в случае, если сохранены его товарный вид,
            потребительские свойства, а также документ, подтверждающий факт и условия покупки указанного Товара.<br/>
            7.2.2. При отказе Покупателя от Товара согласно п.7.2.1. Продавец возвращает ему стоимость возвращенного
            Товара,
            за исключением расходов Продавца, связанных с доставкой возвращенного Покупателем Товара, в течение 10 дней
            с
            даты поступления возвращенного Товара на склад Продавца вместе с заполненным Покупателем заявлением на
            возврат.<br/>
            7.2.3. Если на момент обращения Покупателя аналогичный товар отсутствует в продаже у Продавца, Покупатель
            вправе
            отказаться от исполнения настоящего Соглашения и потребовать возврата уплаченной за указанный Товар денежной
            суммы. Продавец обязан вернуть уплаченную за возвращенный товар денежную сумму в течение 3 дней со дня
            возврата
            Товара.<br/>
            7.2.4. Не подлежат возврату парфюмерно-косметические товары, текстильные товары (хлопчатобумажные, льняные,
            шелковые, шерстяные и синтетические ткани, товары из нетканых материалов типа тканей — ленты, тесьма,
            кружево и
            другие), швейные и трикотажные изделия, нормальное использование которых не предусматривает наличие другой
            одежды между ними и телом, включая купальники, белье, чулочно-носочные изделия и т.п. (в соответствии с
            Перечнем
            непродовольственных товаров надлежащего качества, не подлежащих возврату или обмену на аналогичный товар
            других
            размера, формы, габарита, фасона, расцветки или комплектации, утвержденного Постановлением Правительства РФ
            от
            19 января 1998 г. N 55).<br/>
            7.3. Возврат Товара ненадлежащего качества:<br/>
            7.3.1. Под товаром ненадлежащего качества подразумевается товар, который неисправен и не может обеспечить
            исполнение своих функциональных качеств. Полученный Товар должен соответствовать описанию на Сайте. Отличие
            элементов дизайна или оформления от заявленного на Сайте описания не является признаком ненадлежащего
            качества.<br/>
            7.3.2. Внешний вид и комплектность Товара, а также комплектность всего Заказа должны быть проверены
            Получателем
            в момент доставки Товара.<br/>
            7.3.3. При доставке Товара Покупатель ставит свою подпись в квитанции о доставке в графе: «Заказ принял,
            комплектность полная, претензий к количеству и внешнему виду товара не имею». После получения Заказа
            претензии к
            внешним дефектам товара, его количеству, комплектности и товарному виду не принимаются.<br/>
            7.3.4. Если Покупателю был передан Товар ненадлежащего качества и оное не было заранее оговорено Продавцом,
            Покупатель вправе воспользоваться положениями ст. 18 «Права потребителя при обнаружении в товаре
            недостатков»
            Закона о защите прав потребителей.<br/>
            7.3.5. Требования о возврате уплаченной за товар денежной суммы подлежат удовлетворению в течение 10 дней со
            дня
            предъявления соответствующего требования (ст. 22 Закона РФ «О защите прав потребителей»).<br/>
            7.4. Возврат денежных средств осуществляется посредством возврата стоимости оплаченного Товара на банковскую
            карту или почтовым переводом. Способ должен быть указан в соответствующем поле заявления на возврат Товара,
            которое можно найти здесь.<br/>
            <br/>
            <h2>8. Ответственность</h2><br/>
            8.1. Продавец не несет ответственности за ущерб, причиненный Покупателю вследствие ненадлежащего
            использования
            Товаров, приобретенных в Интернет-магазине.<br/>
            8.2. Продавец не несет ответственности за содержание и функционирование внешних сайтов.<br/>
            <br/>
            <h2>9. Конфиденциальность и защита информации</h2><br/>
            9.1. Персональные данные Пользователя/Покупателя обрабатывается в соответствии с ФЗ «О персональных данных»
            №
            152-ФЗ.<br/>
            9.2. При регистрации на Сайте Пользователь предоставляет следующую информацию: Фамилия, Имя, Отчество,
            контактный номер телефона, адрес электронной почты, дату рождения, пол, адрес доставки товара.<br/>
            9.3. Предоставляя свои персональные данные Продавцу, Посетитель Сайта/Пользователь/Покупатель соглашается на
            их
            обработку Продавцом, в том числе в целях выполнения Продавцом обязательств перед Посетителем
            Сайта/Пользователем/Покупателем в рамках настоящей Публичной оферты , продвижения Продавцом товаров и услуг,
            проведения электронных и sms опросов, контроля результатов маркетинговых акций, клиентской поддержки,
            организации доставки товара Покупателям, проведение розыгрышей призов среди Посетителей Сайта/Пользователей/
            Покупателей, контроля удовлетворенности Посетителя Сайта/Пользователя/Покупателя, а также качества услуг,
            оказываемых Продавцом.<br/>
            9.4. Под обработкой персональных данных понимается любое действие (операция) или совокупность действий
            (операций), совершаемых с использованием средств автоматизации или без использования таких средств с
            персональными данными, включая сбор, запись, систематизацию, накопление, хранение, уточнение (обновление,
            изменение) извлечение, использование, передачу (в том числе передачу третьим лицам, не исключая
            трансграничную
            передачу, если необходимость в ней возникла в ходе исполнения обязательств), обезличивание, блокирование,
            удаление, уничтожение персональных данных.<br/>
            9.4.1. Продавец имеет право отправлять информационные, в том числе рекламные сообщения, на электронную почту
            и
            мобильный телефон Пользователя/Покупателя с его согласия. Пользователь/Покупатель вправе отказаться от
            получения
            рекламной и другой информации без объяснения причин отказа. Сервисные сообщения, информирующие
            Пользователя/Покупателя о заказе и этапах его обработки, отправляются автоматически и не могут быть
            отклонены
            Пользователем/Покупателем.<br/>
            9.4.2 Отзыв согласия на обработку персональных данных осуществляется путем отзыва акцепта настоящей
            Публичной
            оферты.<br/>
            9.5. Продавец вправе использовать технологию «cookies». «Cookies» не содержат конфиденциальную информацию и
            не
            передаются третьим лицам.<br/>
            9.6. Продавец получает информацию об ip-адресе посетителя Сайта www.lamoda.ru. Данная информация не
            используется
            для установления личности посетителя.<br/>
            9.7. Продавец не несет ответственности за сведения, предоставленные Пользователем/Покупателем на Сайте в
            общедоступной форме.<br/>
            9.8. Продавец вправе осуществлять записи телефонных разговоров с Пользователем/Покупателем. При этом
            Продавец
            обязуется: предотвращать попытки несанкционированного доступа к информации, полученной в ходе телефонных
            переговоров, и/или передачу ее третьим лицам, не имеющим непосредственного отношения к исполнению Заказов, в
            соответствии с п. 4 ст. 16 Федерального закона «Об информации, информационных технологиях и о защите
            информации».<br/>
            <br/>
            <h2>10. Срок действия Публичной оферты</h2><br/>
            10.1 Настоящая Публичная оферта вступает в силу с момента ее акцепта Посетителем Сайта/Покупателем, и
            действует
            до момента отзыва акцепта Публичной оферты.<br/>
            <br/>
            <h2>11. Дополнительные условия</h2><br/>
            11.1. Продавец вправе переуступать либо каким-либо иным способом передавать свои права и обязанности,
            вытекающие
            из его отношений с Покупателем, третьим лицам.<br/>
            11.2. Интернет-магазин и предоставляемые сервисы могут временно частично или полностью недоступны по причине
            проведения профилактических или иных работ или по любым другим причинам технического характера. Техническая
            служба Продавца имеет право периодически проводить необходимые профилактические или иные работы с
            предварительным уведомлением Покупателей или без такового.<br/>
            11.3. К отношениям между Пользователем/Покупателем и Продавцом применяются положения Российского
            законодательства.<br/>
            11.4. В случае возникновения вопросов и претензий со стороны Пользователя/Покупателя он должен обратиться к
            Продавцу по телефону или иным доступным способом. Все возникающее споры стороны будут стараться решить путем
            переговоров, при недостижении соглашения спор будет передан на рассмотрение в судебный орган в соответствии
            с
            действующим законодательством РФ.<br/>
            11.5. Признание судом недействительности какого-либо положения настоящего Соглашения не влечет за собой
            недействительность остальных положений.<br/>
            <br/>
        </div>
    </div>
</div>
<script>
    window.addEventListener("load", function () {
        var trigger = document.getElementById("ofertaTrigger");
        var ofertaModalWindow = document.getElementById("ofertaModalWindow");
        var blackBackground = document.getElementById("blackBackground");
        var ofertaModalCloseButton = document.getElementsByClassName("ofertaModalCloseButton")[0];
        ofertaModalCloseButton.addEventListener("click", function () {
            blackBackground.className += " invisible";
            ofertaModalWindow.className += " invisible";

        });
        blackBackground.addEventListener("click", function () {
            this.className += " invisible";
            ofertaModalWindow.className += " invisible";

        });
        trigger.addEventListener("click", function () {

            ofertaModalWindow.className = ofertaModalWindow.className.match(/[0-9A-Za-z\-_]+/);

            blackBackground.className = blackBackground.className.match(/[0-9A-Za-z\-_]+/);
        });
    });
</script>
<script type="text/javascript">
    var <? echo $strObName; ?> =
    new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
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