function calculateRowItem(row_old, currency_type_id_new, exchange_rate_sale) {

    // console.log(currency_type_id_new, exchange_rate_sale)

    let currency_type_id_old = row_old.item.currency_type_id
    let unit_price = parseFloat(row_old.item.unit_price)
    // } else {
    //     unit_price = parseFloat(row_old.item.unit_price) * 1.18
    // }
    let warehouse_id = row_old.warehouse_id

    // console.log(row_old)

    // if (currency_type_id_old === 'PEN' && currency_type_id_old !== currency_type_id_new)
    // {
    //     unit_price = unit_price / exchange_rate_sale;
    // }

    // if (currency_type_id_new === 'PEN' && currency_type_id_old !== currency_type_id_new)
    // {
    //     unit_price = unit_price * exchange_rate_sale;
    // }

    // unit_price = _.round(unit_price, 4);
 

    let row = {
        item_id: row_old.item.id,
        // item_description: row_old.item.description,
        item: row_old.item,
        currency_type_id: currency_type_id_new,
        quantity: row_old.quantity,
        unit_value: 0,
        affectation_igv_type_id: row_old.affectation_igv_type_id,
        affectation_igv_type: row_old.affectation_igv_type,
        total_base_igv: 0,
        percentage_igv: 18,
        total_igv: 0,
        system_isc_type_id: null,
        total_base_isc: 0,
        percentage_isc: 0,
        total_isc: 0,
        total_base_other_taxes: 0,
        percentage_other_taxes: 0,
        total_other_taxes: 0,
        total_plastic_bag_taxes: 0,
        total_taxes: 0,
        price_type_id: '01',
        unit_price: unit_price,
        input_unit_price_value: row_old.input_unit_price_value,
        total_value: 0,
        total_discount: 0,
        total_charge: 0,
        total: 0,
        attributes: row_old.attributes,
        charges: row_old.charges,
        discounts: row_old.discounts,
        warehouse_id: warehouse_id,
        name_product_pdf: row_old.name_product_pdf
    };

    let percentage_igv = 18
    let unit_value = row.unit_price

    if (row.affectation_igv_type_id === '10') {
        unit_value = row.unit_price / (1 + percentage_igv / 100)
    }

    // row.unit_value = _.round(unit_value, 4)

    row.unit_value = unit_value

    let total_value_partial = unit_value * row.quantity


    let total_isc = 0
    let total_other_taxes = 0

    let total_value = total_value_partial 
    let total_base_igv = total_value_partial  + total_isc

    let total_igv = 0

    if (row.affectation_igv_type_id === '10') {
        total_igv = total_base_igv * percentage_igv / 100
    }
    if (row.affectation_igv_type_id === '20') { //Exonerated
        total_igv = 0
    }
    if (row.affectation_igv_type_id === '30') { //Unaffected
        total_igv = 0
    }

    let total_taxes = total_igv + total_isc + total_other_taxes
    let total = total_value + total_taxes

    // row.total_charge = _.round(total_charge, 2)
    // row.total_discount = _.round(total_discount, 2)
    // row.total_charge = _.round(total_charge, 2)
    row.total_value = _.round(total_value, 2)
    row.total_base_igv = _.round(total_base_igv, 2)
    row.total_igv =  _.round(total_igv, 2)
    row.total_taxes = _.round(total_taxes, 2)
    row.total = _.round(total, 2)

 
    
    // console.log(row)
    return row
}

export {calculateRowItem}
