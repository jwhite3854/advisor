<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $data['meta'] ?>
    <link rel="stylesheet" href="<?php echo Url::render('/assets/css/bootstrap-4.3.1.min.css') ?>">
<?php if ( count( $data['more_stylesheets'] ) ): ?>
<?php foreach ( $data['more_stylesheets'] as $stylesheet ): ?>
    <link rel="stylesheet" href="<?php echo $stylesheet['href'] ?>" media="<?php echo $stylesheet['media'] ?>">
<?php endforeach; ?>
<?php endif; ?>
    <link rel="stylesheet" href="<?php echo Url::render('/assets/css/advisor.css') ?>">
    <?php if ( !empty( $data['custom_style'] ) ): ?><?php echo '<style>', $data['custom_style'], '</style>' ?><?php endif; ?>
</head>
<body itemscope itemtype="http://schema.org/WebPage">
<div class="site_container">
    <?php echo $data['nav'] ?>
    <div class="container site_inner">
    <?php echo $data['content'] ?>
    </div>
<?php echo $data['footer'] ?>	
</div>
<script src="<?php echo Url::render('/assets/js/jquery-3.4.1.min.js') ?>"></script>
<script src="<?php echo Url::render('/assets/js/bootstrap-4.3.1.min.js') ?>"></script>
<?php if ( count( $data['more_scripts'] ) ): ?>
    <?php foreach ( $data['more_scripts'] as $script ): ?>
<script src="<?php echo $script['src'] ?>" <?php echo $script['async'] ?> <?php echo $script['defer'] ?> ></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>