
            function displayErrorMessageDialog() {
                var logonButtonUrl = '/';

                
                var messageTitle = 'An error occurred while processing your request.',
		      messageBody = 'Please Logon again or close your browser and retry',
                    logonButtonText = 'Log On';
		 
		
                var $messagePane = $('#mainmessagepane'),        
                $button = $messagePane.find('.button');
		   
                
		$messagePane.find('.messagebox-title').text(messageTitle);
		$messagePane.find('.messagebox-body').text(messageBody);
                $button.text(logonButtonText).click(function () {
                    window.location.pathname = logonButtonUrl;
                    return false;
                });
                
                $('.mainpane').hide();
                $messagePane.show();

                $button.focus();
            }
     

        
                $(document).ready(function() {
                var $authenticationPane = $('#authentication');
                var initialRequest = true;

                // A real application may decide to display some progress UI here, while it initializes

                // Create the forms authentication widget, attaching it to a page element
                var $widgetContainer = $('#logonbox-logonform'),
		      logonboxContainerTop = parseFloat($("#logonbox-container").css("top")) || 0,
                    logonbeltTopshadow = parseFloat($("#logonbelt-topshadow").css("paddingBottom")) || 0,
                    logonbeltBottomshadow = parseFloat($("#logonbelt-bottomshadow").css("paddingBottom")) || 0;
					
                $widgetContainer.ctxsFormsAuthentication({
                    // Whether Cancel button should be displayed - not recommended for logon forms
                    allowCancel: false,

                    // Pre-populated credentials can be inserted into an initial logon form
                    // If these are provided, they may overwrite assistive text within the text boxes
            //      prePopulatedCredentials: {
            //          username: 'johnsmith',
            //          password: 'letmein',
            //          domain: 'acmecorp'
            //      },

                    // URL of image to use as spinner while a form is being submitted
                    formActionSpinnerImageUrl: 'media/Loader.gif',
                    
                    // The action to complete once authentication has succeeded
                    // The authenticationInfo object may provide useful details
                    authenticationSuccess: function(event, authenticationInfo) {
                        var authenticationMethod = authenticationInfo.authenticationType,
                            isPasswordChangeAllowed = authenticationInfo.isPasswordChangeAllowed,
                            isPasswordExpiryNotificationEnabled = authenticationInfo.isPasswordExpiryNotificationEnabled,
                            timeUntilExpiry = authenticationInfo.passwordExpiryTime,
                            authenticationResponse = authenticationInfo.authenticationResponse,
							message = 'User has logged on via ' + authenticationMethod + ' and has ' +
								timeUntilExpiry + ' days remaining until password expires.';

                       
			     	var RedirectUrl = $(authenticationResponse).find('RedirectURL').text();
			    	window.open(RedirectUrl,'_parent');

                        // Hide the logon UI and proceed with the rest of the application
                        $authenticationPane.hide();
                    },
                    
                    // The action to complete if authentication fails
                    // The errorData object may provide an error ID with more details about the problem
                    authenticationError: function (event, authResult) {
                         var errorId = authResult.errorId,
                         authenticationResponse = authResult.authenticationResponse;
                         window.alert('Authentication failed with error ID ' + errorId);
			    initialRequest = true ; //Resetting initialRequest to "true" so that login form may be loaded again
			    getinitialUrl('/home/configuration');
                    },
                    
                    // Code that should run immediately before the widget displays a form
                    // Can be used to prepare the application UI for the display of a new form
                    beforeDisplayForm: function() {
                        // In this example, we make sure that the authentication pane is visible (it starts off hidden)
                        // In a real application we might also hide some progress UI
                        $authenticationPane.show();
                    },
                    
                    // Code that should run immediately after the widget displays a form
                    // Can be used to modify the form UI to suit the application
                    displayForm: function() {},
                    
                    // Function that is responsible for sending and retrieving form data, for example by making AJAX
                    // calls to a server.
                    // This example loads form data from script elements within the page
                    // formData: object containing form field names and values which should be submitted to the server, e.g. as the data attribute in a jQuery AJAX call.
                    // headers: object containing the names and values of HTTP headers which should be included in the request to the server
                    formProvider: function (url, formData, headers, callback) {
                       		 									
					if (initialRequest)
						{
						initialRequest = false;
						$.ajax({
                                			url:url,
                               			 type: "get",
                               			 dataType: "xml",
                               			 data: formData,
										 headers: headers,
                                			success: function(xml,string,jqXHR){
	                           				callback(xml, "ExplicitForms");
                                			},
							error: function(jqXHR){
								displayErrorMessageDialog();
								return;
								}
						       })
						}
    	 				 else{
						$.ajax({
                                			url:url,
                                			type: "POST",
                               			 dataType: "xml",
                               			 data: formData,
										 headers: headers,
                               			 success: function(xml,string,jqXHR){
							       callback(xml, "ExplicitForms");
						     			 },
							 error: function(jqXHR){
								displayErrorMessageDialog();
								return;												
							}
		                  			 })
						}

                        // All form providers are required to call back to the widget using the supplied function,
                        // providing form XML and (optional) name of the forms protocol being used
                        // The form XML should be a Document object, as obtained from XMLHttpRequest.responseXML, for example
                        //callback(formXml, formProtocolName);
                    },
					
					// Reserved space is used to calculate space available for control, if space available is less than needed then scrollbar is presented on screen.
                    reservedSpace: logonboxContainerTop + logonbeltTopshadow + logonbeltBottomshadow
				});
				
		function  getinitialUrl(url) {
                        $.ajax({
                                url:url,
                                type: "get",
                                dataType: "xml",
                                success: function(xml){
                                authManager = $(xml).find('authManager')[0];
                                initialUrl = $(authManager).attr('loginURL');
                              $widgetContainer.ctxsFormsAuthentication('startAuthentication', initialUrl);
                                }
                                })
				}
		getinitialUrl('/home/configuration');  // Start the logon process
		
		// On window resize event plug-in will fire its resize event to find out if it need scrollbar.
		 $(window).resize(function () {
                    if ($widgetContainer.data('ctxsCtxsFormsAuthentication')) {
                        $widgetContainer.ctxsFormsAuthentication('resize');
                    }
                });

                
                
            });
        