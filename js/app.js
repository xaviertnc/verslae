var Ajax, Flash, Pager;
var LocalScripts  = LocalScripts  || { run:function(){} };
var GlobalScripts = GlobalScripts || { run:function(){} };


Ajax = {
	load: function(href)
	{
		$("#page").load(href, function(response,status,xhr)
		{
			if (status=="error") {
				alert("Oops, failed to load page! " + xhr.status + " " + xhr.statusText);
			}
			else {
				Url.init();
				LocalScripts.run();
			}
		});
	},

	pushState: function(href)
	{
		var state = {url:href, title:""};
		history.pushState(state, state.title, state.url);
	},

	bindLinks: function()
	{
		console.log('Bind Links');
		$("a").click(function() { Ajax.pushState(this.href); Ajax.load(this.href); return false });
	}
};


Flash = {
	dismiss: function(type,index) {
		document.getElementById("flash_" + type + "_" + index.toString()).style="display:none";
		return false
	}
};


/**
 * By C. Moller 3 Mei 2016
 * Copied from KL main.js - Mar 2013
 */
var Url = {

	current: '',
	base: '',
	pageref: '',
	query: '',
	query_params: [],
	segments: [],

	init: function()
	{
		Url.current = window.location.href.toString();

		console.log('Url:init(), Url.current =', Url.current);

		var url_info = Url.parse(Url.current);

		Url.base = url_info.base;
		Url.pageref = url_info.pageref; //Excludes Query String! - NM 10 Jun 2013
		Url.query = url_info.query;
		Url.query_params = url_info.query_params;
		Url.segments = url_info.segments;

		//console.log('Url = ', Url);
	},

	parse: function(href)
	{
		var result = { base: '', pageref: '', query: '', query_params: {}, segments: [] };
		var parts = href.split('?');
		result.base = $('base').attr('href');

		result.pageref = parts[0];

		if(parts.length > 1) {
			result.query = parts[1];
			var params = result.query.split('&');
			$.each(params, function(index, value) {
				var pair = value.split('=');
				if(pair.length > 1) {
					result.query_params[pair[0]] = pair[1];
				}
				else {
					result.query_params[pair[0]] = true;
				}
			});
		}

		result.segments = result.pageref.split('/');

		return result;
	},

	//This turns out to be like matching a specific route! Maybe use lib like Sammy or Backbone later...
	match: function(href)
	{
		if(href === Url.pageref) { return true; } else { return false; }
	},

	make: function(params)
	{
		//console.log('Url.make(), query =', Url.query_params);
		//console.log('Url.make(), params =', params);
		var url, paramindex, paramsets = [], i = 0;
		for (paramindex in Url.query_params) {
			if (typeof params[paramindex] === "undefined" && paramindex !== "dlg") {
				paramsets[i] = paramindex + '=' + Url.query_params[paramindex];
				i++;
			}
		}
		for (paramindex in params) {
			paramsets[i] = paramindex + '=' + params[paramindex];
			i++;
		}
		url = Url.pageref + (paramsets.length ? '?' + paramsets.join('&') : '');
		//console.log('Url.make(), url =', url);
		return url;
	}
};


Pager = {
	load: function(pageno)
	{
		var url = Url.make({'p' : $('#'+pageno+' input').val()});
		Ajax.pushState(url);
		Ajax.load(url);
		return false;
	},

	onPageNoEnter: function(event, pageno)
	{
		var keyCode = ('which' in event) ? event.which : event.keyCode;
		if (keyCode == 13) { return Pager.load(pageno); }
	}

};


window.onpopstate = function(e)
{
	//console.dir(e);
	Ajax.load(e.state ? e.state.url : "");
};


$('document').ready(function()
{
	console.log('app.js - document.ready(), Run Global + Local Scripts!');
	Url.init();
	GlobalScripts.run();
	LocalScripts.run();
});
