[{assign var="shop"      value=$oEmailView->getShop()}]
[{assign var="oViewConf" value=$oEmailView->getViewConfig()}]
[{assign var="user"      value=$oEmailView->getUser()}]


[{include file="email/html/header.tpl"}]

[{oxifcontent ident="cc_dsgvo_userdata" object="oCont"}]
    <p>[{$oCont->getFieldData('oxcontent')}]</p>
[{/oxifcontent}]

[{include file="email/html/footer.tpl"}]
