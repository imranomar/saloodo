<?php


namespace App;


class Cts
{
    const BUNDLE_PRODUCT_TYPE = 1;
    const DEFAULT_PRODUCT_TYPE = 0;

    const DISCOUNT_PERCENTAGE_TYPE = 1;
    const DISCOUNT_AMOUNT_TYPE = 0;

    //roles
    const ROLE_ADMIN = 1;
    const ROLE_GUEST = 0;

    //http statuses
    const HTTP_STATUS_UNAUTHORIZED = 401;
    const HTTP_STATUS_OK = 200;

    //price currency
    const CURRENCY_SYMBOL = "€" ;

    //paging
    const ITEMS_PER_PAGE_PAGING = 10;
}
