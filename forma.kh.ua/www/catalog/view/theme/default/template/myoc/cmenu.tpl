<?php if ($cmenus) { ?>
<style type="text/css">
#menu > ul > li > div > ul > li > div {
  display: none; /* hide 3rd level navigation for OpenCart default theme */
}
</style>
<div id="menu">
  <ul>
    <?php foreach ($cmenus as $cmenu) { ?>
    <li<?php if($cmenu['current']) { ?> class="current"<?php } ?>><a href="<?php echo $cmenu['href']; ?>"<?php if($cmenu['popup']) {?> target="_blank"<?php } if($cmenu['href'] == "#") { ?> onclick="return false;"<?php } ?>><?php echo $cmenu['name']; ?></a>
      <?php if ($cmenu['children']) { ?>
      <div>
        <?php for ($i = 0; $i < count($cmenu['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($cmenu['children']) / $cmenu['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($cmenu['children'][$i])) { ?>
          <li<?php if($cmenu['children'][$i]['current']) { ?> class="current"<?php } ?>><a href="<?php echo $cmenu['children'][$i]['href']; ?>"<?php if($cmenu['children'][$i]['popup']) { ?> target="_blank"<?php } ?>><?php echo $cmenu['children'][$i]['name']; ?></a>
            <?php if ($cmenu['children'][$i]['children']) { //3rd level start ?>
            <div>
              <?php for ($m = 0; $m < count($cmenu['children'][$i]['children']);) { ?>
              <ul>
                <?php $n = $m + ceil(count($cmenu['children'][$i]['children']) / $cmenu['children'][$i]['column']); ?>
                <?php for (; $m < $n; $m++) { ?>
                <?php if (isset($cmenu['children'][$i]['children'][$m])) { ?>
                <li<?php if($cmenu['children'][$i]['children'][$m]['current']) { ?> class="current"<?php } ?>><a href="<?php echo $cmenu['children'][$i]['children'][$m]['href']; ?>"<?php if($cmenu['children'][$i]['children'][$m]['popup']) { ?> target="_blank"<?php } ?>><?php echo $cmenu['children'][$i]['children'][$m]['name']; ?></a></li>
                <?php } ?>
                <?php } ?>
              </ul>
              <?php } ?>
            </div>
            <?php } //end 3rd level ?>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
</div>
<?php } ?>