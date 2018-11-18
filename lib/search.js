jQuery(document).ready(function($) {

    'use strict';

    var $form = $('#TumorContentSearch');
    var $TissueDropdown = $('#TissueDropdown');
    var $HierarchiesDropdown = $('#HierarchiesDropdown');
    var $TumorDropdown = $('#TumorDropdown');
	var dJax = null;
    var gJax = null;
    // First do some normalization and load Tissue Type
	$TissueDropdown.prop('disabled', true).children('option:gt(0)').remove();
	$TissueDropdown.children('option:first-child').text('Loading Tissue...');

	$HierarchiesDropdown.prop('disabled', true).children('option:gt(0)').remove();
	$HierarchiesDropdown.children('option:first-child').text('Select Tissue First');
	
	$TumorDropdown.prop('disabled', true).children('option:gt(0)').remove();
	$TumorDropdown.children('option:first-child').text('Select Tissue First');
	
	dJax = $.ajax({
		url : base_url('lib/get_info_for_tissue.php', false, false, false, {type : 'tissue_type'}),
		dataType : 'json'
	})
	.done(function(data, textStatus, jqXHR) {
		_.each(data, function(id, name) {
			 $TissueDropdown.append('<option value="' + id + '">' + name + '</option>');
		});
		$TissueDropdown.prop('disabled', false).children('option:first-child').text('Select Tissue');
		$HierarchiesDropdown.children('option:first-child').text('Select Tissue First');
		$TumorDropdown.children('option:first-child').text('Select Tissue First');
	});
    var tissue_name = null;
    var class_name = null;

    // Setup form-level event listeners
    $form.on('submit', function(event) {
        event.stopPropagation();
        event.preventDefault();

        if (tissue_name !== null) {
            var uri = 'search.php';
            var params = {};
			params['searchtype'] = 'tissue';
			params['tissue'] = tissue_name;
            if (class_name !== null) {
				//params['tumor'] = class_name;
                if (typeof $TumorDropdown.val() === 'string' && $TumorDropdown.val() !== ''){
                    params['tumor'] = $TumorDropdown.val();
                }

            }
            //var referrer = $('#referrer');

            //if(referrer.length > 0){
                //params['ref'] = referrer.val();
            //}

            window.location = base_url(uri, false, false, false, params);
        }
    });
	
	// Populate Tumor Dropdown based on Tissue Dropdown choice
    $TissueDropdown.on('change', function(event) {

        if (dJax !== null) {
            dJax.abort();
        }

        if (gJax !== null){
            gJax.abort();
        }

        var val = $(this).val();

        if (val === '') {
            class_name = null;
            tissue_name = null;

            event.stopPropagation();
            event.preventDefault();

            // Remove any results
            $TumorDropdown.prop('disabled', true).children('option:gt(0)').remove();
            $TumorDropdown.children('option:first-child').text('Select Tissue First');

            $HierarchiesDropdown.prop('disabled', true).children('option:gt(0)').remove();
            $HierarchiesDropdown.children('option:first-child').text('Select Tissue First');

            return false
        }

        $HierarchiesDropdown.prop('disabled', true).children('option:gt(0)').remove();
        $HierarchiesDropdown.children('option:first-child').text('Loading Hierarchies for ' + $TissueDropdown.children('option:selected').text());

        $TumorDropdown.prop('disabled', true).children('option:gt(0)').remove();
        $TumorDropdown.children('option:first-child').text('Select Tissue First');
		
		tissue_name = $TissueDropdown.children('option:selected').text();
		class_name = null;

        dJax = $.ajax({
            url : base_url('lib/get_info_for_tissue.php', false, false, false, {type : 'class_type',tissue : val}),
            dataType : 'json'
        })
        .done(function(data, textStatus, jqXHR) {
            _.each(data, function(id, name) {
                 $HierarchiesDropdown.append('<option value="' + id + '">' + name + '</option>');
            });

            $HierarchiesDropdown.prop('disabled', false).children('option:first-child').text('Select Tumor Hierarchies');

            $TumorDropdown.children('option:first-child').text('Select Hierarchies Next');
        });

    });

    // Populate Gene Dropdown based on Tissue & Tumor selection
    $HierarchiesDropdown.on('change', function(event) {

        if (gJax !== null){
            gJax.abort();
        }

        var val = $(this).val();

        if (val === '') {
            class_name = null;

            event.stopPropagation();
            event.preventDefault();

            // Remove any results
            $TumorDropdown.prop('disabled', true).children('option:gt(0)').remove();
            $TumorDropdown.children('option:first-child').text('Select Hierarchies Next');

            return false
        }

        class_name = $(this).children('option:selected').text();

        $TumorDropdown.prop('disabled', true).children('option:gt(0)').remove();
        $TumorDropdown.children('option:first-child').text('Loading Tumor Type...');

        gJax = $.ajax({
                url : base_url(
                    'lib/get_info_for_tissue.php',
                    false,
                    false,
                    false,
                    {type : 'tumor_type', tissue: tissue_name, class : val}),
                dataType : 'json'
            })
            .done(function(data, textStatus, jqXHR) {
                _.each(data, function(id, name) {
                    $TumorDropdown.append('<option value="'+id+'">'+name+'</option>');
                });

                $TumorDropdown.prop('disabled', false).children('option:first-child').text('Select Tumor');
            });
    });
	
	
	
	var $form1 = $('#GeneContentSearch');
    var $GenesDropdown = $('#GenesDropdown');
    var $GeneInput = $('#GeneInput');
	var dJax = null;
    // First do some normalization and load Tissue Type
	$GenesDropdown.prop('disabled', true).children('option:gt(0)').remove();
	$GenesDropdown.children('option:first-child').text('Loading Gene...');

	$GeneInput.val('');
	
	dJax = $.ajax({
		url : base_url('lib/get_info_for_gene.php', false, false, false, {}),
		dataType : 'json'
	})
	.done(function(data, textStatus, jqXHR) {
		_.each(data, function(id, name) {
			 $GenesDropdown.append('<option value="' + id + '">' + name + '</option>');
		});
		$GenesDropdown.prop('disabled', false).children('option:first-child').text('Select Gene Symbol');
	});
    var gene_name = null;

    // Setup form-level event listeners
    $form1.on('submit', function(event) {
        event.stopPropagation();
        event.preventDefault();

        if (gene_name !== null) {
            var uri = 'gene_browser.php';
            var params = {};
			params['gene']  = gene_name;
			params['start'] = gene_name.slice(0,1).toUpperCase();
			window.location = base_url(uri, false, false, false, params);
		}
    });
	
	// Populate Type Dropdown based on Gene Dropdown choice
    $GenesDropdown.on('change', function(event) {

        if (dJax !== null){
            dJax.abort();
        }

        var val = $(this).val();

        if (val === 'Select Gene') {
            gene_name = null;

            event.stopPropagation();
            event.preventDefault();
			$GeneInput.prop('disabled', false);
            $GeneInput.val('');
            // Remove any results
            return false
        }
		$GeneInput.prop('disabled', true);
        $GeneInput.val('');
        gene_name = $(this).children('option:selected').val();

    });

    // Populate Variant Dropdown based on Gene & Type selection
    $GeneInput.on('change', function(event) {

         if (dJax !== null) {
            dJax.abort();
        }

        var val = $(this).val();

        if (val !== '') {
            gene_name = null;

            event.stopPropagation();
            event.preventDefault();

            // Remove any results
           	$GenesDropdown.prop('disabled', true).children('option:gt(0)').remove();
			$GenesDropdown.children('option:first-child').text('Select Gene Symbol');
			gene_name = $GeneInput.val();
            //return false
        }
    });

});