<?php
    if(!isset($seo_keywords) || !is_array($seo_keywords))
        $seo_keywords = [];

    if(!isset($seo_desc) || !is_string($seo_desc))
        $seo_desc = 'test';

    if(!isset($header_background))
        $header_background = '/img/home-bg.jpg';

    if(isset($header_title))
        $global_title = $header_title . ' - test';
    else
        $global_title = 'test';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?=$global_title;?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?=$seo_desc;?>">
    <meta name="author" content="test">
    <meta property="og:site_name" content="<?=$global_title;?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://<?=$_SERVER['HTTP_HOST'];?>">
    <meta property="og:title" content="<?=$global_title;?>">
    <meta property="og:description" content="<?=$seo_desc;?>">
    <meta name="description" content="<?=$seo_desc;?>">
    <meta property="og:image" content="<?=$header_background;?>">
    <meta name="twitter:author" content="@">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:src" content="<?=$header_background;?>">
    <meta name="twitter:title" content="<?=$global_title;?>">
    <meta name="twitter:description" content="<?=$seo_desc;?>">
    <meta content='text/html; charset=UTF-8' http-equiv='Content-Type'/>
    <link rel="apple-touch-icon" sizes="57x57" href="/icon/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/icon/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/icon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/icon/apple-touch-icon-76x76.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
    <link rel="manifest" href="/icon/site.webmanifest">
    <link rel="mask-icon" href="/icon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/icon/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/icon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!--
    <link rel="alternate" type="application/atom+xml" title="Департамент Тайн - Atom" href="https://www.mikechip.pp.ua/feeds/posts/default" />
    <link rel="alternate" type="application/rss+xml" title="Департамент Тайн - RSS" href="https://www.mikechip.pp.ua/feeds/posts/default?alt=rss" />
    -->
    <link href="/jvendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/jvendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href="/css/clean-blog.css" rel="stylesheet">
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img alt="Глаз колется" class="img-header" src="/img/photo.jpg" />
            Департамент Тайн
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Меню
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Записи</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Рубрики
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php foreach(\FastBlog\Post::getCats() as $id => $cat) { if($id==0) continue;?>
                        <a class="dropdown-item" href="/cat/<?=$id;?>-<?=str_replace(' ', '-', $cat);?>"><?=$cat;?></a>
                    <?php } ?>
                        <a class="dropdown-item" href="/cat/0-Разное"><i>Разное</i></a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">О проекте</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Связаться</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header class="masthead" style="background-image: url('<?=$header_background;?>')">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="site-heading">
                    <h2><?=isset($header_title) ? $header_title : '';?></h2>
                    <span class="subheading"><?=isset($header_desc) ? $header_desc : '';?></span>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div class="container">
