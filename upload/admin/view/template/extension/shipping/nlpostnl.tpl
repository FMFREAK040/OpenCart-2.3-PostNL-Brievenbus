<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-postnl" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_countries_customs; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-nlpostnl" class="form-horizontal">
	   
	   <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general_settings" data-toggle="tab"><?php echo $icon_nlpostnl_settings; ?></a></li>
            <li><a href="#tab-nederland" data-toggle="tab"><?php echo $icon_nlpostnl_tarifs; ?></a></li>
          </ul>
	  
	<div class="tab-content">
    <div class="tab-pane active" id="tab-general_settings">
		
		<div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_use_freeshipping; ?>"><?php echo $entry_use_freeshipping; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($nlpostnl_use_freeshipping) { ?>
                <input type="radio" name="nlpostnl_use_freeshipping" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="nlpostnl_use_freeshipping" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$nlpostnl_use_freeshipping) { ?>
                <input type="radio" name="nlpostnl_use_freeshipping" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="nlpostnl_use_freeshipping" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
        
          
          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_use_mailbox; ?>"><?php echo $entry_use_mailbox; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($nlpostnl_use_mailbox) { ?>
                <input type="radio" name="nlpostnl_use_mailbox" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="nlpostnl_use_mailbox" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$nlpostnl_use_mailbox) { ?>
                <input type="radio" name="nlpostnl_use_mailbox" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="nlpostnl_use_mailbox" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_use_mailbox_track; ?>"><?php echo $entry_use_mailbox_track; ?></span></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($nlpostnl_use_mailbox_track) { ?>
                <input type="radio" name="nlpostnl_use_mailbox_track" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="nlpostnl_use_mailbox_track" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$nlpostnl_use_mailbox_track) { ?>
                <input type="radio" name="nlpostnl_use_mailbox_track" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="nlpostnl_use_mailbox_track" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>
				
		
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
            <div class="col-sm-10">
              <select name="nlpostnl_tax_class_id" id="input-tax-class" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $nlpostnl_tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
		
		
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="nlpostnl_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $nlpostnl_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
		
		
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="nlpostnl_status" id="input-status" class="form-control">
                <?php if ($nlpostnl_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		
		
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="nlpostnl_sort_order" value="<?php echo $nlpostnl_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        
    </div>
  	
          
    
    <div class="tab-pane" id="tab-nederland">  
    
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-netherlands-cost-mailbox-20"><span data-toggle="tooltip" title="<?php echo $text_help_nl_mailbox_20; ?>"><?php echo $entry_netherlands_cost_mailbox_20; ?></span></label>
			<div class="col-sm-10">
              <input type="text" name="nlpostnl_netherlands_cost_mailbox_20" value="<?php echo $nlpostnl_netherlands_cost_mailbox_20; ?>" placeholder="<?php echo $entry_netherlands_cost_mailbox_20; ?>" onkeypress='validate(event)' id="input-netherlands-cost-mailbox_20" class="form-control" />
            </div>
      </div>
	  
    <div class="form-group">
            <label class="col-sm-2 control-label" for="input-netherlands-cost-mailbox-50"><span data-toggle="tooltip" title="<?php echo $text_help_nl_mailbox_50; ?>"><?php echo $entry_netherlands_cost_mailbox_50; ?></span></label>
			<div class="col-sm-10">
              <input type="text" name="nlpostnl_netherlands_cost_mailbox_50" value="<?php echo $nlpostnl_netherlands_cost_mailbox_50; ?>" placeholder="<?php echo $entry_netherlands_cost_mailbox_50; ?>" onkeypress='validate(event)' id="input-netherlands-cost-mailbox_50" class="form-control" />
            </div>
      </div>
	  
    <div class="form-group">
            <label class="col-sm-2 control-label" for="input-netherlands-cost-mailbox-100"><span data-toggle="tooltip" title="<?php echo $text_help_nl_mailbox_100; ?>"><?php echo $entry_netherlands_cost_mailbox_100; ?></span></label>
			<div class="col-sm-10">
              <input type="text" name="nlpostnl_netherlands_cost_mailbox_100" value="<?php echo $nlpostnl_netherlands_cost_mailbox_100; ?>" placeholder="<?php echo $entry_netherlands_cost_mailbox_100; ?>" onkeypress='validate(event)' id="input-netherlands-cost-mailbox_100" class="form-control" />
            </div>
      </div>
	  
    <div class="form-group">
            <label class="col-sm-2 control-label" for="input-netherlands-cost-mailbox-250"><span data-toggle="tooltip" title="<?php echo $text_help_nl_mailbox_250; ?>"><?php echo $entry_netherlands_cost_mailbox_250; ?></span></label>
			<div class="col-sm-10">
              <input type="text" name="nlpostnl_netherlands_cost_mailbox_250" value="<?php echo $nlpostnl_netherlands_cost_mailbox_250; ?>" placeholder="<?php echo $entry_netherlands_cost_mailbox_250; ?>" onkeypress='validate(event)' id="input-netherlands-cost-mailbox_250" class="form-control" />
            </div>
      </div>
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-netherlands-cost-mailbox-2000"><span data-toggle="tooltip" title="<?php echo $text_help_nl_mailbox_2000; ?>"><?php echo $entry_netherlands_cost_mailbox_2000; ?></span></label>
			<div class="col-sm-10">
              <input type="text" name="nlpostnl_netherlands_cost_mailbox_2000" value="<?php echo $nlpostnl_netherlands_cost_mailbox_2000; ?>" placeholder="<?php echo $entry_netherlands_cost_mailbox_2000; ?>" onkeypress='validate(event)' id="input-netherlands-cost-mailbox_2000" class="form-control" />
            </div>
      </div>
	  


	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-netherlands-cost-mailbox-track"><span data-toggle="tooltip" title="<?php echo $text_help_nl_mailbox_track; ?>"><?php echo $entry_netherlands_cost_mailbox_track; ?></span></label>
			<div class="col-sm-10">
              <input type="text" name="nlpostnl_netherlands_cost_mailbox_track" value="<?php echo $nlpostnl_netherlands_cost_mailbox_track; ?>" placeholder="<?php echo $entry_netherlands_cost_mailbox_track; ?>" onkeypress='validate(event)' id="input-netherlands-cost-mailbox-track" class="form-control" />
            </div>
      </div>
      
      <div class="form-group">
            <label class="col-sm-2 control-label" for="input-netherlands-cost-medium"><span data-toggle="tooltip" title="<?php echo $text_help_nl_medium; ?>"><?php echo $entry_netherlands_cost_medium; ?></span></label>
			<div class="col-sm-10">
              <input type="text" name="nlpostnl_netherlands_cost_medium" value="<?php echo $nlpostnl_netherlands_cost_medium; ?>" placeholder="<?php echo $entry_netherlands_cost_medium; ?>" onkeypress='validate(event)' id="input-netherlands-cost-medium" class="form-control" />
            </div>
      </div>
	  
	  
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="entry_netherlands_cost_large"><span data-toggle="tooltip" title="<?php echo $text_help_nl_large; ?>"><?php echo $entry_netherlands_cost_large; ?></span></label>
			<div class="col-sm-10">
              <input type="text" name="nlpostnl_netherlands_cost_large" value="<?php echo $nlpostnl_netherlands_cost_large; ?>" placeholder="<?php echo $entry_netherlands_cost_large; ?>" onkeypress='validate(event)' id="input-netherlands-cost-large" class="form-control" />
            </div>
      </div>
      
    
    </div>
      
    
    </div> 
    </form>
  </div>
</div>
</div>
</div>
<hr>
<?php echo "<center><a href=\"https://nl.linkedin.com/in/jochemvandenanker/\">Jochem van den Anker</a> for Opencart 2.3.0.x</center>"; ?>
<?php echo $footer; ?>