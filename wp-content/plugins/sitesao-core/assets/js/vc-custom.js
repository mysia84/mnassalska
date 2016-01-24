function pricing_table_feature_remove(element){
	var $this = jQuery(element);
	$this.closest('tr').remove();
	return false;
}
function pricing_table_feature_add(element){
	var $this = jQuery(element);
	var option_list = $this.closest('.pricing-table-feature-list'),
		option_table = option_list.find('table tbody');
	var tmpl = jQuery(dhvcL10n.pricing_table_feature_tmpl);
	option_table.append(tmpl);
	return false;
}
;(function ($) {
	"use strict";
	if(_.isUndefined(window.vc)) window.vc = {};
	vc.edit_form_callbacks.push(function() {
		var model = this.$el;
		var pricing_table_feature = model.find('.pricing-table-feature-list');
		if(pricing_table_feature.length){
			var features = [];
			pricing_table_feature.find('table tbody tr').each(function(){
				var $this = $(this);
				var feature = {};
				feature['content'] = $this.find('#content').val();
				features.push(feature);
			});
			if(_.isEmpty(features)){
				this.params.features='';
			}else{
				var features_json = JSON.stringify(features);
				this.params.features = base64_encode(features_json);
			}
		}
	});
	
	var Shortcodes = vc.shortcodes;
	window.DHVCCarousel = window.VcTabsView.extend({
		render:function () {
            window.DHVCCarousel.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DHVCCarousel.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dhvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dhvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dh_carousel_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            vc.shortcodes.create({shortcode:'dh_carousel_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dh_carousel_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dh_carousel_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dh_carousel_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
	});
	window.DHVCCarouselItem = window.VcTabView.extend({
		render:function () {
            window.DHVCCarouselItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DHVCCarouselItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dh_carousel_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dh_carousel_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
	});
	window.DHVCTestimonial = window.VcTabsView.extend({
		render:function () {
            window.DHVCTestimonial.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DHVCTestimonial.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dhvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dhvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dh_testimonial_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            vc.shortcodes.create({shortcode:'dh_testimonial_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dh_testimonial_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dh_testimonial_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dh_testimonial_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
	});
	window.DHVCTestimonialItem = window.VcTabView.extend({
		render:function () {
            window.DHVCTestimonialItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DHVCTestimonialItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dh_testimonial_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dh_testimonial_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
	});
	window.DHVCPricingTable = window.VcTabsView.extend({
		render:function () {
            window.DHVCPricingTable.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DHVCPricingTable.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dhvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dhvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dh_pricing_table_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            if(tabs_count  >= 5 ){
            	alert(window.dhvcL10n.pricing_table_max_item_msg);
            	return false;
            }
            vc.shortcodes.create({shortcode:'dh_pricing_table_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dh_pricing_table_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dh_pricing_table_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dh_pricing_table_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
	});
	window.DHVCPricingTableItem = window.VcTabView.extend({
		render:function () {
            window.DHVCPricingTableItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DHVCPricingTableItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
        	if(this.$tabs.find('[data-element_type=dh_pricing_table_item]').length >= 5){
        		alert(window.dhvcL10n.pricing_table_max_item_msg);
            	return false;
        	}
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dh_pricing_table_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dh_pricing_table_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
	});
	window.DHVCTimeline = window.VcTabsView.extend({
		render:function () {
            window.DHVCTimeline.__super__.render.call(this);
            var params = this.model.get('params');
            this.$el.data('timeline-type',params.type);
            return this;
        },
        ready:function (e) {
            window.DHVCTimeline.__super__.ready.call(this, e);
            return this;
        },
        changeShortcodeParams:function (model) {
        	window.DHVCTimeline.__super__.changeShortcodeParams.call(this, model);
        	var params = model.get('params');
        	this.$el.data('timeline-type',params.type);
        	var timeline = this.$el;
        	this.$tabs.find('[data-element_type=dh_timeline_item]').each(function(index,tab){
        		var tab_params = $(tab).data('model').get('params');
        		tab_params.badge_type = timeline.data('timeline-type');
        		$(tab).data('model').save({params:tab_params});
        	}) ;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dhvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dhvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dh_timeline_item]').length,
                badge_type = this.$el.data('timeline-type'),
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            vc.shortcodes.create({shortcode:'dh_timeline_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id, badge_type:badge_type}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dh_timeline_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dh_timeline_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dh_timeline_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
	});
	window.DHVCTimelineItem = window.VcTabView.extend({
		render:function () {
            window.DHVCTimelineItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DHVCTimelineItem.__super__.ready.call(this, e);
            return this;
        },
        changeShortcodeParams:function (model) {
        	window.DHVCTimelineItem.__super__.changeShortcodeParams.call(this, model);
        	var params = model.get('params');
        	var timeline = this.$el.closest('[data-element_type="dh_timeline"]');
        	params.badge_type = timeline.data('timeline-type');
        	model.save({params:params});
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dh_timeline_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dh_timeline_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
	});
})(window.jQuery);



if(_.isUndefined(vc)) var vc = {};
;(function ($) {
	"use strict";
	var DHVCViewShortcode = Backbone.View.extend({
		 initialize: function() {
			 vc.shortcodes.bind('add', this.addShortcode, this);
			 vc.shortcodes.bind('reset', this.resetShortcode, this);
			 this.modal_template = $('<div id="dhvc-view-view-shortcode" class="vc_modal fade" style="display:none">\
					 	<div class="vc_modal-dialog modal-dialog" style="width:600px;margin:10% auto 0">\
					 		<div class="vc_modal-content">\
					 			<div class="vc_modal-header">\
					 				<a class="vc_close" aria-hidden="true" data-dismiss="modal" href="#">\
					 					<i class="vc_icon"></i>\
					 				</a>\
					 				<h3 id="dhvc-view-view-shortcode-dialog-title" class="vc_modal-title">Shortcode</h3>\
					 			</div>\
					 			<div class="vc_modal-body">\
		 							<textarea style="width:100%;min-height:200px;resize: none;" onfocus="this.select();" readonly="readonly"></textarea>\
		 						</div>\
					 		</div>\
					 	</div>');
			 if($('#wpb-element-settings-modal-template').length){
					this.modal_template = $('<div id="dhvc-view-view-shortcode" class="modal" style="display:none">\
									            <div class="modal-header">\
									                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\
									                <h3>Shortcode</h3>\
									            </div>\
									            <div class="modal-body">\
									                <div class="vc_row-fluid">\
														<textarea style="width:100%;min-height:200px;resize: none;" onfocus="this.select();" readonly="readonly"></textarea>\
									                </div>\
									            </div>\
									            <div class="modal-footer text-center">\
									                <button class="button-primary wpb_save_edit_form" data-dismiss="modal" aria-hidden="true">Save</button>\
									                <button class="button" data-dismiss="modal" aria-hidden="true">Cancel</button>\
									          </div>\
									        </div>');
			 }
			 $('body').append(this.modal_template);
		 },
		 escapeParam:function (value) {
		    return value.replace(/"/g, '``');
		 },
		 unescapeParam:function (value) {
		    return value.replace(/(\`{2})/g, '"');
		 },
		 addShortcode: function(model){
			 this.addButton(model);
		 },
		 resetShortcode: function(shortcodes){
			 var that = this;
			 _.each(shortcodes.models,function(model){
				 that.addButton(model);
			 });
		 },
		 addButton: function(model){
			 var that = this;
			 var el = $('[data-model-id="'+model.get('id')+'"]');
				el.find('.controls').each(function(){
					if(!$(this).find('.dhvc-view-shortcode').length){
						$('<a class="vc_control dhvc-view-shortcode column_shortcode" href="#" title="View Shortcode"><i class="vc_icon"></i></a>').insertBefore($(this).find('.column_edit'));
					}
				});
				el.find('.vc_controls').each(function(){
				if(!$(this).find('.dhvc-view-shortcode').length){
					$('<a class="vc_control-btn dhvc-view-shortcode vc_control-btn-shortcode" href="#" title="View Shortcode"><span class="vc_btn-content"><span class="icon-white"></span></span></a>').insertBefore($(this).find('.vc_control-btn-edit'));
				}
			 });
			 $('.dhvc-view-shortcode').on('click',function(e){
				 e.stopPropagation();
				 e.preventDefault();
				
				var parent = $(this).closest('[data-model-id]').data('model');
				
				var models = _.filter(_.values(vc.storage.data), function (model) {
					return model.id === parent.id;
	            });
				
				models = _.sortBy(models, function (model) {
	                return model.order;
	            });
				
				var content = _.reduce(models, function (memo, model) {
	                model.html = that.createShortcodeString(model);
	                return memo + model.html;
	            }, '', this);
				
				$(that.modal_template).find('textarea').text(content);
				$(that.modal_template).modal('show');
			 });
		 },
		createShortcodeString:function (model) {
            var params = _.extend({}, model.params),
                params_to_string = {};
            _.each(params, function (value, key) {
                if (key !== 'content' && !_.isEmpty(value)) params_to_string[key] = this.escapeParam(value);
            }, this);
            
            var content = this._getShortcodeContent(model),
                is_container = _.isObject(vc.map[model.shortcode]) && _.isBoolean(vc.map[model.shortcode].is_container) && vc.map[model.shortcode].is_container === true;
            if(!is_container  && _.isObject(vc.map[model.shortcode]) && !_.isEmpty(vc.map[model.shortcode].as_parent)) is_container = true;
           
            return wp.shortcode.string({
                tag:model.shortcode,
                attrs:params_to_string,
                content:content,
                type:!is_container && _.isUndefined(params.content) ? 'single' : ''
            });
        },
		_getShortcodeContent:function (parent) {
		    var that = this,
		        models = _.sortBy(_.filter(vc.storage.data, function (model) {
		            // Filter children
		            return model.parent_id === parent.id;
		        }), function (model) {
		            // Sort by `order` field
		            return model.order;
		        }),
		
		        params = {};
		    _.extend(params, parent.params);
		 
		    if (!models.length) {
		
		        if(!_.isUndefined(window.switchEditors) && _.isString(params.content) && window.switchEditors.wpautop(params.content)===params.content) {
		            params.content = window.vc_wpautop(params.content);
		        }
		
		        return _.isUndefined(params.content) ? '' : params.content;
		    }
		    return _.reduce(models, function (memo, model) {
		        return memo + that.createShortcodeString(model);
		    }, '');
		}
	 });
	 $(function(){
		 var dhvcviewshortcode = new DHVCViewShortcode();
	 });
})(window.jQuery);