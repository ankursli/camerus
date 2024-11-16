<?php echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>'; ?>
<dsOrder xmlns="www.rentplus.be/webservices/Order.xsd">
    <Order>
        <Customer_Address_Line_1>{!! $address_line_1 !!}</Customer_Address_Line_1>
        <Customer_Postal_code>{!! $postal_code !!}</Customer_Postal_code>
        <Date_Period_Out>{{ $salon_period_out }}</Date_Period_Out>
        <Date_Period_In>{{ $salon_period_in }}</Date_Period_In>
        <Delivery_Postal_Code>{{ $salon_cp }}</Delivery_Postal_Code>
        <Delivery_City>{!! $salon_ville_name !!}</Delivery_City>
        <Delivery_Country>{{ $salon_country }}</Delivery_Country>
        <Date_Availibility_Out>{{ $availibility_period_out }}</Date_Availibility_Out>
        <Date_Availibility_In>{{ $availibility_period_in }}</Date_Availibility_In>
        <Price_Category_Code>{{ $salon_rate }}</Price_Category_Code>
        <Location_Key>{{ $salon_location_key }}</Location_Key>
        @if(!empty($items))
            <OrderLines>
                @foreach($items as $item)
                    @php
                        $item_data = $item->get_data();
                        $product = wc_get_product($item_data['product_id']);
                        $rent_family = sprintf("%02d", get_field('rent_family', $item_data['product_id']));
                        $rent_group = sprintf("%02d", get_field('rent_group', $item_data['product_id']));
                        $pr_sku = str_replace('-GB','',$product->get_sku());
                        $article_key = $rent_group.$rent_family.$pr_sku
                    @endphp

                    <OrderLine>
                        <Article_Key>{{ $article_key }}</Article_Key>
                        <Number>{{ $item_data['quantity'] }}</Number>
                        <ModuleNumber>{{ $module_number }}</ModuleNumber>
                    </OrderLine>
                @endforeach
            </OrderLines>
        @endif
        <OrderModules>
            <OrderModule>
                <ModuleNumber>{{ $module_number }}</ModuleNumber>
                <Hall>{!! $hall !!}</Hall>
                <Passage>{!! $passage !!}</Passage>
                <Stand>{!! $stand !!}</Stand>
                <Module_Name>{!! $module_name !!}</Module_Name>
            </OrderModule>
        </OrderModules>
    </Order>
</dsOrder>