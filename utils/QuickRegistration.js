<label><?php echo '1234567890'; ?></label>
/**
 * Copyright © 2009-2014 Rhizome Networks LTD All rights reserved.
 *
 * Created by  Tomer Schilman on 02/12/14.
 * <p/>
 */
CG_HOST = "//api.contentglass.com";
//CG_HOST = "//local.contentglass.com";
CG_CORE_URL = CG_HOST + "/core";
CG_REGISTRATION_URL = CG_HOST + "/server_api/s1/application";

wordPressCgPlugin = {
    XDEBUG: 13020,

    /**
     * self jquery reference for preventing conflicts
     */
    $: null,

    loaderArray: [],

    loaderIndex: -1,


    newUser: "new user",

    registeredUser: "registered user",
    /**
     * install the jquery and jquery ui if necessary and continue to the callback function.
     * @param callBackFunction
     */
    installJquery: function(callBackFunction){
        var that = this;
        this.loaderArray = [];
        if (this.shouldInstallQuery()){
            this.loaderArray.push({src: CG_CORE_URL + "/libs/jquery/jquery.min.js", condition: this.shouldInstallQuery()});
            this.loaderArray.push({src: CG_CORE_URL + "/libs/jquery_ui/js/jquery-ui.min.js", handler: this.setJqueryNoConflict});
        } else if (typeof this.$ !== "undefined" && this.$ != null && typeof this.$.ui !== "undefined"){
            //we need to continue with the regular loading all the jquery file are present
        } else {
            //jquery present but not jquery ui
            if (typeof this.$ !== "undefined" && this.$ != null && typeof this.$.ui === "undefined"){
                var tempjQuery = jQuery;
                jQuery = this.$;
                this.loaderArray.push({src: CG_CORE_URL + "/libs/jquery_ui/js/jquery-ui.min.js", handler: function(){
                    that.setJqueryNoConflict;
                    jQuery = tempjQuery;
                }});
            } else if (typeof jQuery !== "undefined" && typeof jQuery.ui === "undefined"){
                this.loaderArray.push({src: CG_CORE_URL + "/libs/jquery_ui/js/jquery-ui.min.js", handler: this.setJqueryNoConflict});
            } else if (typeof this.$.ui === 'undefined'){
                this.loaderArray.push({src: CG_CORE_URL + "/libs/jquery_ui/js/jquery-ui.min.js", handler: this.setJqueryNoConflict});
            }
        }
        if (this.loaderArray.length > 0){
            this.loaderArray[this.loaderArray.length-1].handler = function(){
                that.setJqueryNoConflict();
                callBackFunction(that);
            };
            this.loaderIndex = 0;
            this.installNextScript();
        } else {
//            that.setJqueryNoConflict();
            callBackFunction(that);
        }
    },

    /**
     * called when the user press the get app id link
     * create the form for a quick app id registration
     */
    openQuickRegistration: function(){
        this.installJquery(this.continueOpenQuickRegistration);
    },

    /**
     * continue the quick registration loading
     * receive the self reference
     * @param that
     */
    continueOpenQuickRegistration: function(that){
        var $ = that.$;
        var dialog = $("#cg-wordpress-plugin-quick-reg-dialog");
        if (dialog.length == 0){
            dialog = $(document.createElement("div"));
        } else {
            dialog.dialog("destroy");
            dialog.empty();
        }
        dialog.attr("id", "cg-wordpress-plugin-quick-reg-dialog");
        dialog.append(that.initQuickRegistrationContent());
        dialog.dialog({
            dialogClass: "cg",
            draggable: true,
            resizable: false,
            closeOnEscape: true,
            width:500,
            height: 300,
            modal: true,
            title: "Quick application registration",
            buttons:
                [
                    {text: "Cancel", click:function(){
                        $(this).dialog("close");
                        $(this).dialog("destroy");
                        $(this).remove();
                    }},
                    {text: "Create", click:function(){
                        that.clearInputErrors();
                        var dialog = this;
                        var result = that.checkInputs();
                        if (result.status){
                            var callback = function(data){
                                var appIdInput = $("#cg_button_app_id");
                                appIdInput.val(data.id.id);
                                that.showUserInformation(data);
                                $(this).dialog("close");
                                $(this).dialog("destroy");
                                $(this).remove();
                              };
                            if (that.registrationType == that.newUser){
                                that.sendNewUserRequest(callback);
                            } else {
                                that.sendRegisteredUserRequest(callback);
                            }
                        } else {
                            that.showInputError(result);
                        }
                    }}
                ]
        });
    },

    /**
     * show the newly information to the user
     * @param data
     */
    showUserInformation: function(data){
        var $ = this.$;
        var notification = $("#cg-wordpress-plugin-notification-message");
        notification.empty();

        var label;
        if (data.id.type == "newUser"){
            label = $(document.createElement("label"));
            label.html("User created successfully");
            notification.append(label);

            label = $(document.createElement("label"));
            label.text("User name: " + data.id.name);
            notification.append(label);

            label = $(document.createElement("label"));
            label.text("Email: " + data.id.mail);
            notification.append(label);

            label = $(document.createElement("label"));
            label.text("Password: " + data.id.pwd);
            notification.append(label);

            label = $(document.createElement("label"));
            label.text("An email have been sent to you.");
            notification.append(label);

            label = $(document.createElement("label"));
            label.html("to change the password, click  <a href='http://www.contentglass.com/user/login'>here</a> and enter account setting.");
            notification.append(label);
        }
        label = $(document.createElement("label"));
        label.html("The application has been created and the appId has been set into AppId field");
        notification.append(label);

        label = $(document.createElement("label"));
        label.html("Press save content to save");
        notification.append(label);

        notification.show();
    },

    /**
     * remove all the input error indications
     */
    clearInputErrors: function(){
        var $ = this.$;
        var appNameInput = $("#cg-wordpress-plugin-quick-reg-app-name");
        var userNameInput = $("#cg-wordpress-plugin-quick-reg-user-name");
        var userEmailInput = $("#cg-wordpress-plugin-quick-reg-user-email");
        var userPassInput = $("#cg-wordpress-plugin-quick-reg-user-password");

        appNameInput.removeClass("cg-wordpress-plugin-input-error");
        userNameInput.removeClass("cg-wordpress-plugin-input-error");
        userEmailInput.removeClass("cg-wordpress-plugin-input-error");
        userPassInput.removeClass("cg-wordpress-plugin-input-error");
    },

    showInputError: function(errors){
        var $ = this.$;
        for (var index in errors.errorsResults){
            if (errors.errorsResults.hasOwnProperty(index)){
                var errorInput = errors.errorsResults[index];
                errorInput.addClass("cg-wordpress-plugin-input-error");
            }
        }
    },

    /**
     * check all the form fields and if one of the fields are empty then set the result.status = -1
     * and add all the problematic fields to the result.errorsResults array
     * @returns {{status: boolean, errorsResults: Array}}
     */
    checkInputs: function(){
        var $ = this.$;
        var result = {
            status: true,
            errorsResults: []
        };
        var appNameInput = $("#cg-wordpress-plugin-quick-reg-app-name");
        var userNameInput = $("#cg-wordpress-plugin-quick-reg-user-name");
        var userEmailInput = $("#cg-wordpress-plugin-quick-reg-user-email");
        var userPassInput = $("#cg-wordpress-plugin-quick-reg-user-password");
        if (appNameInput.length > 0 && appNameInput.val().length === 0){
            result.errorsResults.push(appNameInput);
        }
        if (userNameInput.length > 0 && userNameInput.val().length === 0){
            result.errorsResults.push(userNameInput);
        }
        if (userPassInput.length > 0 && userPassInput.val().length === 0){
            result.errorsResults.push(userPassInput);
        }
        if (userEmailInput.length > 0 && userEmailInput.val().length === 0){
            result.errorsResults.push(userEmailInput);
        } else if (userEmailInput.length > 0 && !this.validateEmail(userEmailInput.val())){
            result.errorsResults.push(userEmailInput);
        }
        if (result.errorsResults.length > 0){
            result.status = false;
        }
        return result;
    },

    /**
     * send a rest request to the  server
     * @param url
     * @param params
     * @param callbackFunction
     */
    sendRequestToServer: function(url, params, callbackFunction){
        var that = this;
        params["XDEBUG_SESSION_START"] = this.XDEBUG;
        var $ = this.$;
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: params,
            crossDomain: true,
            beforeSend: function (xhr) {
                xhr.withCredentials = true;
                xhr.setRequestHeader("X-Requested-With", "X-PINGRHZ");
            },
            success: function (data) {
                callbackFunction(data);
            },
            error: function (xhr, status, error) {
                console.log("Error in httpPOST url='" + url + "'. Details: " + xhr.responseText);
                if (typeof xhr.responseJSON !== "undefined" ){
                    var errorMessage = that.parseErrorMessage($.parseJSON(xhr.responseJSON.message).message);
                    var errorLabel = $("#cg-wordpress-plugin-quick-reg-error-label");
                    errorLabel.text(errorMessage);
                    errorLabel.show();
                    setTimeout(function(){
                        errorLabel.text("");
                        errorLabel.fadeOut("slow");
                    }, 5000);
                }
            }
        });
    },

    parseErrorMessage: function(errorMessage){
        var startPos;
        while((startPos = errorMessage.indexOf("<em")) > -1){
            var endPos = errorMessage.indexOf(">");
            errorMessage = errorMessage.substring(0,startPos) + errorMessage.substring(endPos+1, errorMessage.length);
            startPos = errorMessage.indexOf("</em>");
            errorMessage = errorMessage.substring(0,startPos) + errorMessage.substring(startPos + 5, errorMessage.length);
        }
        return errorMessage;
    },

    sendRegisteredUserRequest: function(callbackFunction){
        var $ = this.$;
        var appDef = this.prepareNewAppDaf();
        var appNameInput = $("#cg-wordpress-plugin-quick-reg-app-name");
        var appName = typeof appNameInput.attr("value") != "undefined" ? appNameInput.attr("value") :  appNameInput.prop("value");
        var userNameInput = $("#cg-wordpress-plugin-quick-reg-user-name");
        var userName = typeof userNameInput.attr("value") != "undefined" ? userNameInput.attr("value") :  userNameInput.prop("value");
        var userPassInput = $("#cg-wordpress-plugin-quick-reg-user-password");
        var userPass = typeof userPassInput.attr("value") != "undefined" ? userPassInput.attr("value") : userPassInput.prop("value");
        appDef["data"]["name"] = appName;
        var params = {
            user: userName,
            pwd: userPass,
            app_def: appDef
        }
        this.sendRequestToServer(CG_REGISTRATION_URL + "/create-app-for-existing-user", params, callbackFunction);
    },

    sendNewUserRequest: function(callbackFunction){
        var $ = this.$;
        var appDef = this.prepareNewAppDaf();
        var appNameInput = $("#cg-wordpress-plugin-quick-reg-app-name");
        var appName = typeof appNameInput.attr("value") != "undefined" ? appNameInput.attr("value") :  appNameInput.prop("value");
        var userNameInput = $("#cg-wordpress-plugin-quick-reg-user-name");
        var userName = typeof userNameInput.attr("value") != "undefined" ? userNameInput.attr("value") :  userNameInput.prop("value");
        var userEmailInput = $("#cg-wordpress-plugin-quick-reg-user-email");
        var userEmail = typeof userEmailInput.attr("value") != "undefined" ? userEmailInput.attr("value") : userEmailInput.prop("value");
        appDef["data"]["name"] = appName;
        var params = {
            userName: userName,
            userEmail: userEmail,
            app_def: appDef
        }
        this.sendRequestToServer(CG_REGISTRATION_URL + "/register-user-and-create-app", params, callbackFunction);
    },

    initQuickRegistrationContent: function(){
        var $ = this.$;
        var div = $(document.createElement('div'));
        div.append(this.createErrorLabel());
        div.append(this.createUserTypeRadioButtons());
        div.append(this.createFormContent());
        return div;
    },

    createErrorLabel: function(){
        var $ = this.$;
        var div = $(document.createElement('div'));
        div.addClass("cg-wordpress-plugin-quick-reg-error-label-div");
        var label = $(document.createElement("label"));
        label.attr("id", "cg-wordpress-plugin-quick-reg-error-label");
        label.text("");
        label.hide();
        div.append(label);
        return div;
    },

    /**
     * create the radio buttons div for the user type
     * @returns {*|jQuery|HTMLElement}
     */
    createUserTypeRadioButtons: function(){
        var $ = this.$;
        var that = this;
        var div = $(document.createElement('div'));

        var radio = $(document.createElement('input'));
        radio.attr("type", "radio");
        radio.attr("name", "userType");
        radio.attr("id", "cg-wordpress-plugin-quick-reg-new-user");
        radio.attr("value", this.newUser);
        radio.addClass("cg-wordpress-plugin-quick-reg-user-type");
        radio.prop('checked', true);
        radio.bind("change", function(){
            that.changeUserInput($(this).attr("value"));
        });
        div.append(radio);
        var label = $(document.createElement('label'));
        label.attr("for", "cg-wordpress-plugin-quick-reg-new-user");
        label.addClass("cg-wordpress-plugin-quick-reg-user-type-label");
        label.html("New user");
        div.append(label);

        radio = $(document.createElement('input'));
        radio.attr("type", "radio");
        radio.attr("name", "userType");
        radio.attr("id", "cg-wordpress-plugin-quick-reg-registered-user");
        radio.attr("value", this.registeredUser);
        radio.addClass("cg-wordpress-plugin-quick-reg-user-type");
        radio.bind("change", function(){
            that.changeUserInput($(this).attr("value"));
        });
        div.append(radio);
        label = $(document.createElement('label'));
        label.attr("for", "cg-wordpress-plugin-quick-reg-registered-user");
        label.addClass("cg-wordpress-plugin-quick-reg-user-type-label");
        label.html("Registered user");
        div.append(label);
        this.registrationType = this.newUser;
        return div;
    },
    createFormContent: function(){
        var $ = this.$;
        var table = $(document.createElement('table'));
        table.attr("id", "cg-wordpress-plugin-quick-reg-app-data-table");
        table.append(this.createUserNameRow());
        table.append(this.createEmailRow());
        table.append(this.createAppNameRow());
        return table;
    },

    /**
     * create a table row for the application input data
     * @param labelText
     * @param inputType
     * @param inputId
     * @returns {*|jQuery|HTMLElement}
     */
    createTableRow: function(labelText, inputType, inputId){
        var $ = this.$;
        var tr = $(document.createElement('tr'));
        var td = $(document.createElement('td'));
        var label = $(document.createElement('label'));
        label.text(labelText);
        label.attr("for", inputId);
        label.addClass("cg-wordpress-plugin-quick-reg-text-input-label");
        td.append(label);
        tr.append(td);

        td = $(document.createElement('td'));
        var input = $(document.createElement('input'));
        input.attr("type", inputType);
        input.attr("id", inputId);
        input.attr("name", inputId);
        input.addClass("cg-wordpress-plugin-quick-text-input");
        td.append(input);
        tr.append(td);

        return tr;
    },

    /**
     * creates the user name table row
     * @returns {*|jQuery|HTMLElement}
     */
    createUserNameRow: function(){
        return this.createTableRow("User name: ", "text", "cg-wordpress-plugin-quick-reg-user-name");
    },

    /**
     * creates the user email table row
     * @returns {*|jQuery|HTMLElement}
     */
    createEmailRow: function(){
        return this.createTableRow("Email: ", "text", "cg-wordpress-plugin-quick-reg-user-email");
    },

    /**
     * create the user password table row
     * @returns {*|jQuery|HTMLElement}
     */
    createPasswordRow: function(){
        return this.createTableRow("Password: ", "password", "cg-wordpress-plugin-quick-reg-user-password");
    },

    /**
     * create the application id table row
     * @returns {*|jQuery|HTMLElement}
     */
    createAppNameRow: function(){
        return this.createTableRow("Application name: ", "text", "cg-wordpress-plugin-quick-reg-app-name");
    },

    changeUserInput: function(userType){
        var $ = this.$;
        var table = $("#cg-wordpress-plugin-quick-reg-app-data-table");
        table.empty();
        table.append(this.createUserNameRow());
        if (userType == this.newUser){
            table.append(this.createEmailRow());
        } else {
            table.append(this.createPasswordRow());
        }
        table.append(this.createAppNameRow());
        this.registrationType = userType;
    },

    /**
     * Prepare the new structure of appDef
     * @returns {Object} new structure of application def
     */
    prepareNewAppDaf: function () {
        return {
            status: "active",
            data: {
                settings: {
                    app_id: "",
                    type: "rhz.cg.web.cgbutton",
                    status_reason: ""
                },
                reviews: {},
                payment: {},
                stats: {}
            },
            created_date: "",
            updated_date: ""
        };
    },

    //--------------------------------------------------------------------------------------------------
    //jQuery installation functions
    //--------------------------------------------------------------------------------------------------

    /**
     * Check if jQuery need to be installed. We install jQuery if none is installed or that the installed
     * version is lower than 1.9
     *
     * @returns {boolean} true if need to be installed
     */
    shouldInstallQuery: function () {
        if (this.$ == null){
            if (typeof jQuery == 'undefined') return true;

            if (jQuery.fn.jquery != null && jQuery.fn.jquery.length > 1) {
                var major = parseInt(jQuery.fn.jquery.split(".")[1]);
                return  ( typeof jQuery == 'function' && major < 9);
            } else {
                return true;
            }
        } else {
            return false;
        }
    },

    /**
     * Load JS file
     * @param url
     * @param callback
     */
    loadScript: function (url, callback) {
        if (url.charAt(0) == "/" && url.charAt(1) != "/") {
            url = CG_CORE_URL + url;
        }
        //console.log("loading " + url);
        var that = this;
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.async = false;
        if (script.readyState) {  //IE
            script.onreadystatechange = function () {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    that.onScriptLoaded(script, callback);
                }
            };
        } else {  //Others
            script.onload = function () {
                that.onScriptLoaded(script, callback);
                //console.log("Loaded: " + script.src);
            };
        }

        script.src = url;
        var head = document.getElementsByTagName("head")[0];
        head.insertBefore(script, head.firstChild);
    },

    /**
     * Invoked for the onload or onreadystatechange events of loaded script
     * @param script
     * @param callback
     */
    onScriptLoaded: function (script, callback) {
        // Remove the script
        if (script.parentNode) {
            script.parentNode.removeChild(script);
        }
        if (callback != null) {
            callback.call(this, window.cg.jQuery);
        }
        this.installNextScript();
    },

    /**
     * General purpose function for preparing script tags for the scripts defined by array
     * @private
     */
    installNextScript: function prepareScripts() {
        if (this.loaderArray != null && this.loaderIndex < this.loaderArray.length) {
            var def = this.loaderArray[this.loaderIndex++];
            if (def["condition"] != false) {
                var src = def["src"];
                var handler = null;
                if (def["handler"] != null) {
                    handler = def["handler"];
                }
                this.loadScript(src, handler);
            } else {
                this.installNextScript();
            }
        }
    },

    /**
     * Prevent conflict between the last installed jQuery and possibly previous version that was installed.
     * To prevent the conflict we do two things:
     * 1. The latest installed jQuery is set on the "cg" namespace
     * 2. Return the control in $ and jQuery to former installed version (or other API that uses these token)
     * <p/>
     * After running this function and for the rest of our code - if we refer to jQuery, usually passing into
     * closure function we use "cg.jQuery" or "cg.$" and not "jQuery" or "$"
     *
     */
    setJqueryNoConflict: function () {
        if (typeof  jQuery !== "undefined"){
            //install loaded jquery on cg namespace
            window.wordPressCgPlugin.jQuery = jQuery;
            window.wordPressCgPlugin.$ = window.wordPressCgPlugin.jQuery;

            //for debug
            //console.log(jQuery.fn.jquery); //will show the new version
            //console.log($.fn.jquery);  //will show the new version

            //If required return the control in $ and and jQuery to previously installed version / api
            //After running this command window.$ and window.jQuery will hold the previously installed jQuery version.
            jQuery.noConflict(true);

            //console.log(jQuery.fn.jquery); //will show the old version
            //console.log($.fn.jquery);  //will show the old version

            //Important! and from now on if we refer to jQuery, usually passing into closure function we use "cg.jQuery" or "cg.$"
        }
    },

    //--------------------------------------------------------------------------------------------------
    //utils functions
    //--------------------------------------------------------------------------------------------------

    /**
     * return true if the string is in valid email format otherwise returns false
     * @param email
     * @returns {boolean}
     */
    validateEmail: function (email){
        var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        var valid = emailReg.test(email);

        if(!valid) {
            return false;
        } else {
            return true;
        }
    }
}
