<!DOCTYPE html>
<html lang="en">
    <head>

        <!-- Basic Page Needs
        ================================================== -->
        <meta charset="utf-8">
        <title>lezaz:setting(title,My Site)</title>
        <meta name="description" content="">	
        <meta name="author" content="">

        <!-- Mobile Specific Metas
        ================================================== -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- Favicons
        ================================================== -->
        <link rel="icon" href="{{upload}}site/lezaz:setting(icon)" type="image/x-icon" />
              <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{upload}}site/lezaz:setting(icon)">
              <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{upload}}site/lezaz:setting(icon)">
              <link rel="apple-touch-icon-precomposed" href="{{upload}}site/lezaz:setting(icon)">

              <!-- CSS --- JS -----
              ================================================== -->
              <link rel="stylesheet" href="{{this}}css/style.css" type="text/css"/>
        <link rel="stylesheet" href="{{this}}css/responsiveslides.css" type="text/css"/>
        <link rel="stylesheet" href="{{this}}css/default.min.css" type="text/css"/>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script type='text/javascript' src='http://maps.googleapis.com/maps/api/js?v=3&amp;language=en-GB&amp;libraries=places'></script>
        <script src="{{this}}js/jquery.min.js" type="text/javascript"></script>
        <script src="{{this}}js/wp-emoji-release.min.js" type="text/javascript"></script>
        <script src="{{this}}js/responsiveslides.min.js" type="text/javascript"></script>
        <script src="{{this}}js/main.js" type="text/javascript"></script>

        <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
        <link href='{{this}}css/plugin_googlemap3.css' rel='stylesheet' type='text/css'/>
        <style type="text/css">
            img.wp-smiley,
            img.emoji {
                display: inline !important;
                border: none !important;
                box-shadow: none !important;
                height: 1em !important;
                width: 1em !important;
                margin: 0 .07em !important;
                vertical-align: -0.1em !important;
                background: none !important;
                padding: 0 !important;
            }
.subject {
    font-size: 20px !important;
}            
 img.client {
    /* height: auto; */
    width: 108px;
    height: 116px;
border: 4px rgba(177, 177, 177, 0.28) solid;
 }
        </style>
    </head>

    <body class="home blog">
        <div class="container">
            <header>
                <a class="logo" href="/" style="background-image: url({{upload}}site/lezaz:setting(logo1))"></a>
                <div class="header-content">
                    <div class="searchbox">
                        <form role="search" method="get" id="searchform">
                            <input type="submit" class="searchbox-submit" id="searchsubmit" value=" ">
                            <input class="searchbox-text" type="text" placeholder="Search" name="s" id="s">
                        </form>
                        <div class="clear"></div>
                    </div>
                    <div class="topmenu">
                        <ul id="menu-top-menu" class="menu">
                            <lezaz:sql id="topmenu_sql" sql="Select * From pages where page_active=1 and (page_place=2 or page_place=3) and linklabel='en' ORDER BY page_sort ASC" show="1000">                              
                            <li id="menu-item-lezaz#topmenu_sql[id]" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-lezaz#topmenu_sql[id]"><a href="[if:"%topmenu_sql:url-var%","%topmenu_sql:url%[else]/page/%topmenu_sql:id%/%topmenu_sql:page_name%"end if]">%topmenu_sql:page_name%</a></li>                                                                  
                            </lezaz:sql>         
                        </ul>            </div>
                    <div class="clear"></div>
                    <div class="mainmenu">
                        <ul id="menu-main-menu" class="menu">
                            [sql:"mainmenu_sql","Select * From pages where page_active=1 and (page_place=1 or page_place=3) and linklabel='en' ORDER BY page_sort ASC","1000","                                
                            <li id="menu-item-%mainmenu_sql:id%" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-%mainmenu_sql:id%"><a href="[if:"%mainmenu_sql:url-var%","%mainmenu_sql:url%[else]/page/%mainmenu_sql:id%/%mainmenu_sql:page_name%"end if]">%mainmenu_sql:page_name%</a></li>                                                                  
                            "end sql] 
                            
                            *************** param1= lezaz$param1<br> param2= lezaz$param2 ********************************
                        </ul>          
                    </div> 
                </div><!--heder-content end here-->
                <div class="clear"></div>      	    
            </header>