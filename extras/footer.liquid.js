<!--Footer Start-->
{% include section.settings.footer_layout %}
<!--End of Footer-->
{% schema %}
{
   "name": "Footer",
   "class": "section-footer",
   "settings": [
      {
         "type": "select",
         "id": "footer_layout",
         "label": "Select header layout",
         "options": [
            {
               "value": "footer-1",
               "label": "Design 1"
            }
         ]
      },
      {
        "type": "image_picker",
        "id": "image",
        "label": "Background Image",
        "info": "Footer area background image"
      },
 	  {
         "type": "header",
         "content": "Contact information"
      },
      {
         "type": "checkbox",
         "id": "footer_content_enable",
         "label": "Show footer contact info"
      },
      {
         "type": "textarea",
         "id": "footer_about_us",
         "label": "Contact About Us"
      },
      {
         "type": "textarea",
         "id": "footer_contact_info",
         "label": "Contact info"
      },
      {
         "type": "header",
         "content": "Footer Menu Widgets"
      },
      {
         "type": "link_list",
         "id": "footer_menu_1",
         "label": "Menu 1"
      },
	  {
         "type": "link_list",
         "id": "footer_menu_2",
         "label": "Menu 2"
      },
	  {
         "type": "link_list",
         "id": "footer_menu_3",
         "label": "Menu 3"
      },
	  {
         "type": "link_list",
         "id": "footer_menu_4",
         "label": "Menu 4 (Center)"
      },
	  {
         "type": "link_list",
         "id": "footer_menu_5",
         "label": "Menu 5 (Bottom)"
      },
	  {
         "type": "header",
         "content": "Newsletter"
      },
      {
         "type": "checkbox",
         "id": "footer_newsletter_enable",
         "label": "Show newsletter sign-up"
      },
      {
         "type": "text",
         "id": "mailing_list_form_action",
         "label": "MailChimp form action URL",
         "info": "[Find your MailChimp form action URL](https://docs.shopify.com/manual/configuration/store-customization/communicating-with-customers/accounts-and-newsletters/get-a-mailchimp-form-action-url/)."
      },
      {
         "type": "text",
         "id": "heading",
         "label": "Heading",
         "default": "Sign up for our Newsletter!"
      },
      {
         "type": "checkbox",
         "id": "footer_social_enable",
         "label": "Show social media icons",
         "info": "Add accounts in Social media section"
      },
      {
         "type": "header",
         "content": "Bottom Footer"
      },
      {
        "type": "textarea",
        "id": "footer_content_text",
        "label": "Contact text",
        "info": "Use basic HTML to format text"
      }
   ]
}

{% endschema %}

<script>
   (function($) {
      var MenuJn = (function (window, undefined) {
         var init = function() {
            $('.BtnMenuJn').on('click', function(e) {
               e.preventDefault();
               $('.MegaMenuJn').removeClass('oculto');
            });

            $('.BtnCerrarMenuJn').on('click', function(e) {
               e.preventDefault();
               $('.MegaMenuJn').addClass('oculto');
            });

            $('a[data-submenu]').each(function() {
               $(this).on('click', function(e) {
                  e.preventDefault();
                  var submenu = '#'+$(this).data('submenu');
                  if($(submenu).hasClass('oculto')) {
                     $(submenu).removeClass('oculto');
                     $(this).find('i').removeClass('fa-chevron-right').addClass('fa-times');
                  } else {
                     $(submenu).addClass('oculto');
                     $(this).find('i').removeClass('fa-times').addClass('fa-chevron-right');
                  }
               });
            });
         };

         return {
            init : function() {
               init();
            }
         };

      })(window, undefined);

      MenuJn.init();
   })(jQuery);
</script>