<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/module.png" alt=""/> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a
                        onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
            </div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table id="module" class="list">
                    <thead>
                    <tr>
                        <td class="left"><?php echo $entry_value; ?></td>
                        <td class="left"><?php echo $entry_phone; ?></td>
                        <td class="left"><?php echo $entry_add; ?></td>
                    </tr>
                    </thead>
                    <?php $module_row = 0; ?>
                    <tbody id="insert_after">
                    <?php
                    $have_default = false;
                    foreach ($modules as $module) {
                        if ($module[value] == 'default'){
                            $have_default = true;
                        }
                    }?>
                    <?php foreach ($modules as $module) { ?>
                    <?php if ($module[value] != 'default'){ ?>
                    <tr id="module-row<?php echo $module_row; ?>">
                        <td class="left">
                            <input style="width: 90%; width: calc(100% - 14px);" type="text" class="source_variable" data-change="<?php echo $module_row; ?>"
                                   name="clientsource_module[<?php echo $module_row; ?>][value]"
                                   value="<?php echo $module[value]?>">
                        </td>
                        <td class="left">
                            <input type="text" name="clientsource_module[<?php echo $module_row; ?>][phone1]"
                                   value="<?php echo $module[phone1]?>"><br>
                            <input type="text" name="clientsource_module[<?php echo $module_row; ?>][phone2]"
                                   value="<?php echo $module[phone2]?>">
                        </td>
                        <td>
                            <a style="display: inline-block;" onclick="remove_l($(this));"
                               class="button remove_line"><?php echo $button_remove; ?></a>
                        </td>
                    </tr>
                    <?php }else{ ?>
                    <tr id="module-row<?php echo $module_row; ?>">
                        <td class="left">
                            <?php echo $entry_default; ?>
                            <input type="hidden" name="clientsource_module[<?php echo $module_row; ?>][value]" value="default">
                        </td>
                        <td class="left">
                            <input type="text" name="clientsource_module[<?php echo $module_row; ?>][phone1]"
                                   value="<?php echo $module[phone1]?>"><br>
                            <input type="text" name="clientsource_module[<?php echo $module_row; ?>][phone2]"
                                   value="<?php echo $module[phone2]?>">
                        </td>
                        <td></td>
                    </tr>
                    <?php } ?>
		    <?php $module_row++; ?>
                    <?php } ?>
                    <?php if ($have_default == false){ ?>
                    <tr id="module-row<?php echo $module_row; ?>">
                        <td class="left">
                            <?php echo $entry_default; ?>
                            <input type="hidden" name="clientsource_module[<?php echo $module_row; ?>][value]" value="default">
                        </td>
                        <td class="left">
                            <input type="text" name="clientsource_module[<?php echo $module_row; ?>][phone1]"
                                   value=""><br>
                            <input type="text" name="clientsource_module[<?php echo $module_row; ?>][phone2]"
                                   value="">
                        </td>
                        <td></td>
                    </tr>
                    <?php $module_row++; ?>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </form>
        </div>
    </div>
</div>

<script>
    var $row = <?php echo $module_row;?>;
    function addModule() {
        var add = '<tr id="module-row' + $row + '"><td class="left"><input name="clientsource_module[' + $row + '][value]" value=""></td><td class="left"><input name="clientsource_module[' + $row + '][phone1]" value=""><br><input name="clientsource_module[' + $row + '][phone2]" value=""></td><td><a style="display: inline-block;" onclick="remove_l($(this));" class="button remove_line"><?php echo $button_remove; ?></a></td></tr>';
        $('#insert_after').append(add);
        $row++;
    }
    function remove_l ($this) {
        $this.closest('tr').remove();
    }
</script>

<?php echo $footer; ?>
