(function(a){
    a.fn.extend({
        ctxsDisplayPane:function(){
            a(".mainpane").hide();
            this.ctxsLocalize().show();
            return this
        }
        ,ctxsWrapAsMessagePane:function(){
            var b=this.html(),c='<div class="ctxsui-messagebox"><div class="messagebox-middle"><div class="messagebox-middlebody">'+b+"</div></div></div >";
            return this.html(a(c))
        }
        ,ctxsCenterOfBelt:function(){
            var i=a("#resources-container"),g=this.width(),f=this.height(),d,h;
            if(i.is(":hidden")){
                d=a("body").height()/2;
                h=a("body").width()/2
            }
            else{
                d=i.position().top+(i.height()/2);
                h=i.position().left+(i.width()/2)
            }
            var c=0;
            var e=i.find("#appshortcuts-heading");
            if(e.length){
                c=e.height()||0
            }
            var j=(d-(f/2))-c,b=h-(g/2);
            CTXS.trace("alert","ctxsCenterOfBelt: vertical location = {0}. Belt.position.top = {1}. Belt.height = {2}. Element height = {3}".format(j,i.position().top,i.height(),f));
            CTXS.trace("alert","ctxsCenterOfBelt: horizontal location = {0}. Belt.position.left = {1}. Belt.width = {2}. Element width = {3}".format(b,i.position().left,i.width(),g));
            this.css({
                position:"fixed",left:b,top:j
            }).show();
            return this
        }
        ,ctxsCreateScrollPane:function(){
            this.jScrollPane({
                verticalGutter:0,enableKeyboardNavigation:false,contentWidth:1
            });
            this.find(".jspVerticalBar .jspTrack").append('<div class="jspTrackBg" />');
            this.find(".jspVerticalBar .jspDragTop").after('<div class="jspDragMiddle" />');
            this.mousedown(function(){
                a(this).addClass("hide-outline")
            });
            return this
        }
        ,ctxsUpdateScrollPane:function(){
            var b=this.data("jsp");
            if(b){
                b.reinitialise();
                this.find(".jspVerticalBar .jspTrack").append('<div class="jspTrackBg" />');
                this.find(".jspVerticalBar .jspDragTop").after('<div class="jspDragMiddle" />')
            }
            return this
        }
        ,ctxsScrollToTop:function(){
            var b=this.data("jsp");
            if(b){
                b.scrollToY(0)
            }
            return this
        }
        ,ctxsBindFocusWithin:function(d){
            d=d||{};
            var b=d.selector||":tabbable",c=this;
            this.ctxsUnbindFocusWithin();
            this.bind("keydown.cycle",function(f){
                if(f.keyCode==a.ui.keyCode.TAB){
                    var e=c.find(b);
                    if(f.shiftKey){
                        if(document.activeElement==e.first().get(0)){
                            e.last().focus();
                            return false
                        }
                        
                    }
                    else{
                        if(document.activeElement==e.last().get(0)){
                            e.first().focus();
                            return false
                        }
                        
                    }
                    
                }
                return true
            });
            return this
        }
        ,ctxsUnbindFocusWithin:function(){
            return this.unbind("keydown.cycle")
        }
        ,ctxsEnableArrowNavigation:function(e){
            e=e||{};
            var c=e.selector||":tabbable";
            var d=this.find(c),b=d.length;
            this.bind("keydown.arrows",function(f){
                if(f.keyCode==a.ui.keyCode.DOWN||f.keyCode==a.ui.keyCode.UP){
                    var g=a.inArray(document.activeElement,d);
                    if(f.keyCode==a.ui.keyCode.DOWN){
                        a(d[(g+1)%b]).focus()
                    }
                    else{
                        if(f.keyCode==a.ui.keyCode.UP){
                            a(d[(b+(g-1))%b]).focus()
                        }
                        
                    }
                    
                }
                
            });
            return this
        }
        ,ctxsDisableArrowNavigation:function(){
            return this.unbind("keydown.arrows")
        }
        ,ctxsUnifyHoverFocus:function(d){
            d=d||{};
            var c=this,b=d.selector||":tabbable",e=d.hoverClass||"active";
            if(d.exclude!==undefined){
                b=b+":not("+d.exclude+")"
            }
            this.on("mouseenter.unify",b,function(){
                if(!a(this).is(":tabbable")){
                    a(this).find(":tabbable").first().focus()
                }
                else{
                    a(this).focus()
                }
                
            });
            this.on("focusin.unify",b,function(){
                c.find(b).removeClass(e);
                a(this).addClass(e)
            });
            this.on("focusout.unify",b,function(){
                a(this).removeClass(e)
            });
            return this
        }
        ,ctxsUnbindHoverFocusEvents:function(){
            this.off("mouseenter.unify");
            this.off("focusin.unify");
            this.off("focusout.unify");
            return this
        }
        ,ctxsHandleEscapeKeyInDialog:function(){
            var b=this;
            this.on("keyup.escape",".button",function(d){
                if(d.keyCode==a.ui.keyCode.ESCAPE){
                    var c=b.find(".cancelDialogButton.button");
                    if(c.size()==1){
                        c.click();
                        CTXS.Events.publish(CTXS.Events.resources.relinquishFocus)
                    }
                    else{
                        CTXS.trace("alert","CTXS.alertBox does not have a cancel button.")
                    }
                    return false
                }
                return true
            });
            return this
        }
        ,ctxsUnbindEscapeKeyInDialog:function(){
            return this.off("keyup.escape")
        }
        ,ctxsPlaceFocusOnFirstElement:function(c){
            c=c||{};
            var b=c.selector||":focusable";
            this.find(b).first().focus();
            return this
        }
        ,ctxsReapplyText:function(){
            return this.each(function(){
                var b=a(this);
                b.text(b.text())
            })
        }
        ,ctxsCreateDialog:function(c){
            var b=c.open;
            c.open=function(f,e){
                a("input.searchbox-textbox").attr("disabled","disabled");
                if(b){
                    b(f,e)
                }
                
            };
            var d=c.close;
            c.close=function(f,e){
                a("input.searchbox-textbox").attr("disabled",false);
                CTXS.Events.publish(CTXS.Events.resources.relinquishFocus);
                if(d){
                    d(f,e)
                }
                
            };
            this.ctxsDialog(c);
            return this
        }
        ,ctxsMakeScrollable:function(){
            if(CTXS.ClientInfo.isMobileDevice()){
                this.addClass("mobileScroll")
            }
            else{
                this.ctxsCreateScrollPane()
            }
            return this
        }
        ,
    });
    window.CTXS=a.extend(window.CTXS||{},{
        displayMessagePane:function(b){
            a(".mainpane").hide();
            return a("#mainmessagepane").html(b).ctxsWrapAsMessagePane().ctxsDisplayPane()
        }
        ,showAppsView:function(b){
            if(CTXS.usingSwitcher()){
                return a("#resources-switcher").ctxsSwitcher("showAppsView",b)
            }
            
        }
        ,showDesktopsView:function(b){
            if(CTXS.usingSwitcher()){
                return a("#resources-switcher").ctxsSwitcher("showDesktopsView",b)
            }
            
        }
        ,usingSwitcher:function(){
            return a(".switcher-button").length>0
        }
        ,alertBox:function(c,b){
            var e=a("#alertbox");
            if(e.data("ctxsCtxsDialog")){
                e.ctxsDialog("close");
                e.ctxsDialog("destroy")
            }
            if(c=="close"){
                return null
            }
            var d=b||{};
            d.content=c;
            return e.ctxsCreateDialog(d)
        }
        ,popupsEnabled:function(c){
            var b=window.open("","testwindow","height=1,width=1,left=700000,top=7000000,menubar=no,scrollbars=no,status=no,resizable=no,toolbar=no");
            setTimeout(function(){
                if(!b||b.outerHeight===0){
                    if(b){
                        b.close()
                    }
                    CTXS.alertBox(c)
                }
                else{
                    if(b){
                        b.close()
                    }
                    
                }
                
            }
            ,25)
        }
        
    })
})(jQuery);
(function(f){
    window.CTXS=window.CTXS||{};
    CTXS.LoginFormGenerator={
        generateLoginFormFromXml:function(j,l,h){
            var k=f("<form class='form insertPoint' action='' />");
            var i=e(j);
            CTXS.RequirementNodeProcessor.addFieldsForEachRequirement(i,k,j);
            CTXS.LabelHighlighter.highlightFieldsThatNeedHighlightingAndTagInputs(i,k);
            CTXS.FormActions.populateForm(k,l);
            CTXS.RequirementNodeProcessor.addLoadingSpinner(k,h);
            return k
        }
        
    };
    function e(i){
        var h=f("Requirement",i);
        if(h.length==0){
            CTXS.External.reportParsingError("must have at least one requirement node")
        }
        return h
    }
    CTXS.LabelHighlighter={
        highlightFieldsThatNeedHighlightingAndTagInputs:function(h,i){
            h.each(function(){
                var j=this;
                g(j,i)
            })
        }
        
    };
    function g(j,i){
        var h=f("Label Type",j).text();
        f("HighlightFields CredentialID",j).each(function(){
            var k=f(this).text();
            var l=d(k,i);
            c(l);
            var m=b(l,i);
            a(m,h)
        })
    }
    function d(h,i){
        return f(i).find("#"+h)
    }
    function c(h){
        h.addClass("highlightedInput")
    }
    function b(i){
        var h=f(i).closest(".field").find("label");
        if(h.length!=1){
            CTXS.External.reportParsingError("Could not find an appropriate number of labels for this Credential ID")
        }
        return h
    }
    function a(i,h){
        i.addClass("highlightedField"+h);
        i.text("*"+i.text())
    }
    
})(jQuery);
(function(f){
    window.CTXS=window.CTXS||{};
    var c="CancelButtonText";
    CTXS.ButtonGenerator={
        generateButtons:function(k,l,h){
            var j=f("<div />").addClass("field").addClass("buttonsrow"),i=f("<div />").addClass("buttonscontainer").addClass("right");
            f.each(k,function(m,n){
                if(n.label.type!="none"||n.credential.type!="none"){
                    CTXS.External.reportParsingError("odd button field - it includes a label")
                }
                b(n,i)
            });
            if(h){
                d(l,i)
            }
            j.append(i);
            return j
        }
        
    };
    function b(j,i){
        var h=e(j.credential.id,j.input.button);
        i.append(h)
    }
    function e(i,h){
        return f("<a />").attr("id",i).attr("href","#").addClass("button").text(h)
    }
    function d(i,h){
        var j=a(i);
        if(j){
            h.append(j)
        }
        
    }
    function a(i){
        var h=f(c,i);
        if(h.length>1){
            CTXS.External.reportParsingError("Can't have more than one CancelButtonText node")
        }
        if(h.length==1){
            return g(h.text())
        }
        else{
            return null
        }
        
    }
    function g(h){
        return f("<a />").attr("id","chimeraCancelBtn").attr("href","#").addClass("button").text(h)
    }
    
})(jQuery);
(function(f){
    window.CTXS=window.CTXS||{};
    CTXS.RequirementNodeProcessor={
        addFieldsForEachRequirement:function(w,z,y){
            var v=0;
            var x=[];
            w.each(function(){
                var A=CTXS.RequirementNodeParser.parseRequirementsNode(this);
                if(A.input.button!=null){
                    x.push(A)
                }
                else{
                    if(x.length>0){
                        z.append(CTXS.ButtonGenerator.generateButtons(x,y,v<1));
                        v+=x.length;
                        x=[]
                    }
                    z.append(c(z,A))
                }
                
            });
            if(x.length>0){
                z.append(CTXS.ButtonGenerator.generateButtons(x,y,v<1));
                v+=x.length
            }
            f("input, select",z).each(function(){
                f(this).change(function(){
                    f(this).addClass("editedCredential")
                })
            })
        }
        ,addLoadingSpinner:function(w,v){
            var x=f("<div class='spinner' style='visibility: hidden' />").append(f("<img src='"+v+"' alt='' />"));
            w.find(".buttonsrow").append(x)
        }
        
    };
    function c(x,w){
        if(w.input.button!=null){
            CTXS.External.reportParsingError("button should have been sorted elsewhere")
        }
        var v=f("<div/>").addClass("field").addClass("CredentialType"+w.credential.type);
        CTXS.InputFieldGenerator.addInputFieldOrLabel(v,w);
        return v
    }
    CTXS.RequirementNodeParser={
        parseRequirementsNode:function(x){
            var y=f("Credential",x);
            if(y.length!=1){
                CTXS.External.reportParsingError("could not parse Credential node")
            }
            var v=f("Label",x);
            if(v.length!=1){
                CTXS.External.reportParsingError("could not parse Label node")
            }
            var w=f("Input",x);
            if(w.length!=1){
                CTXS.External.reportParsingError("could not parse Input node")
            }
            return{
                credential:o(y),label:q(v),input:j(w)
            }
            
        }
        
    };
    function o(x){
        var w=f("ID",x);
        if(w.length>1){
            CTXS.External.reportParsingError("could not parse credential id")
        }
        var y=null;
        if(w.length==1){
            y=w.text()
        }
        var v=f("Type",x);
        if(v.length!=1){
            CTXS.External.reportParsingError("could not parse credential type")
        }
        return{
            id:y,type:v.text()
        }
        
    }
    function q(v){
        var x=f("Type",v);
        if(x.length!=1){
            CTXS.External.reportParsingError("could not parse label type")
        }
        var w=x.text();
        if(w=="none"||w==""){
            return{
                type:"none"
            }
            
        }
        else{
            if(w=="image"){
                return{
                    type:w,src:f("Binary",v).text()
                }
                
            }
            else{
                if(w=="plain"||w=="heading"||w=="information"||w=="warning"||w=="error"||w=="confirmation"||w=="inprogress"){
                    var A=f("Text",v);
                    if(A.length!=1){
                        CTXS.External.reportParsingError("could not find text for label")
                    }
                    var z=[];
                    var y=f("HighlightFields CredentialID",v);
                    y.each(function(){
                        var B=f(this).text();
                        z.push(B)
                    });
                    return{
                        type:w,text:A.text(),highlightFields:z
                    }
                    
                }
                
            }
            
        }
        CTXS.External.reportParsingError("Unrecognised label type :"+w)
    }
    function j(B){
        var A=f("AssistiveText",B);
        if(A.length>1){
            CTXS.External.reportParsingError("could not parse input AssistiveText")
        }
        var x=null;
        if(A.length==1){
            x=A.text()
        }
        var D=n(f("Text",B));
        var v=u(f("RadioButton",B));
        var C=t(f("ComboBox",B));
        var w=b(f("MultiComboBox",B));
        var y=g(f("Button",B));
        var z=r(f("CheckBox",B));
        return{
            assistiveText:x,text:D,radioButton:v,comboBox:C,multiComboBox:w,button:y,checkBox:z
        }
        
    }
    function n(B){
        if(B.length!=1){
            return null
        }
        var x=false;
        var y=f("Secret",B).text();
        if(y=="true"||y=="True"){
            x=true
        }
        var C=false;
        var z=f("ReadOnly",B).text();
        if(z=="true"||z=="True"){
            C=true
        }
        var D=false;
        var u=f("Immutable",B).text();
        if(u=="true"||u=="True"){
            D=true
        }
        var E=false;
        var t=f("Hidden",B).text();
        if(t=="true"||t=="True"){
            E=true
        }
        var v=f("InitialValue",B).text();
        var A=f("Constraint",B).text();
        var w=A==".+";
        return{
            hidden:E,secret:x,readOnly:C,Immutable:D,initialValue:v,mustNotBeEmpty:w
        }
        
    }
    function u(v){
        return t(v)
    }
    function t(w){
        if(w.length!=1){
            return null
        }
        var y=f("InitialSelection",w);
        var v=null;
        if(y.length==1){
            v=y.text()
        }
        else{
            if(y.length>1){
                CTXS.External.reportParsingError("must only have one inital selection node")
            }
            
        }
        var x=e(w,false);
        return{
            initialSelection:v,displayValues:x
        }
        
    }
    function b(v){
        if(v.length!=1){
            return null
        }
        var w=e(v,true);
        return{
            displayValues:w
        }
        
    }
    function e(x,z){
        var w=f("DisplayValues",x);
        if(w.length!=1){
            CTXS.External.reportParsingError("must have a displayValues node")
        }
        var y=[];
        var v=f("DisplayValue",w);
        v.each(function(){
            var E=f(this);
            var D=f("Display",E);
            if(D.length!=1){
                CTXS.External.reportParsingError("Every DisplayValue node must have a Display node")
            }
            var A=f("Value",E);
            if(A.length!=1){
                CTXS.External.reportParsingError("Every DisplayValue node must have a Value node")
            }
            if(z){
                var B=f("Select",E);
                if(B.length>1){
                    CTXS.External.reportParsingError("Must have at most 1 select node in a DisplayValue node")
                }
                
            }
            var C;
            if(z){
                C={
                    display:D.text(),value:A.text(),selected:(B.text()=="true"||B.text()=="True")
                }
                
            }
            else{
                C={
                    display:D.text(),value:A.text()
                }
                
            }
            y.push(C)
        });
        return y
    }
    function g(v){
        if(v.length!=1){
            return null
        }
        return v.text()
    }
    function r(w){
        if(w.length!=1){
            return null
        }
        var v=false;
        var x=f("InitialValue",w).text();
        if(x=="true"||x=="True"){
            v=true
        }
        return{
            initialValue:v
        }
        
    }
    CTXS.InputFieldGenerator={
        addInputFieldOrLabel:function(w,x){
            var z=f("<div/>").addClass("right");
            if(x.input.radioButton!=null){
                CTXS.LabelGenerator.addLabel(w,x);
                var y=f("<div/>").addClass("radioButtons");
                s(x.input.radioButton,y,x.credential.id);
                y.find("label:first-of-type").after(CTXS.AssistiveTextGenerator.generateInformationIconWithTooltip(x));
                z.append(y);
                w.append(z)
            }
            else{
                if(x.input.checkBox!=null){
                    a(x,z);
                    z.append(CTXS.AssistiveTextGenerator.generateInformationIconWithTooltip(x));
                    w.append(z)
                }
                else{
                    if(x.input.comboBox!=null){
                        CTXS.LabelGenerator.addLabel(w,x);
                        y=l(x);
                        m(x.input.comboBox,y);
                        z.append(y);
                        z.append(CTXS.AssistiveTextGenerator.generateInformationIconWithTooltip(x));
                        w.append(z)
                    }
                    else{
                        if(x.input.multiComboBox!=null){
                            CTXS.LabelGenerator.addLabel(w,x);
                            y=l(x).attr("multiple","multiple");
                            m(x.input.multiComboBox,y);
                            z.append(y);
                            z.append(CTXS.AssistiveTextGenerator.generateInformationIconWithTooltip(x));
                            w.append(z)
                        }
                        else{
                            if(x.input.text!=null){
                                CTXS.LabelGenerator.addLabel(w,x);
                                var y=CTXS.InputFieldGenerator.generateTextField(x);
                                var v=CTXS.AssistiveTextGenerator.generateInlineAssistiveTextNode(x);
                                z.append(v).append(y);
                                w.append(z)
                            }
                            else{
                                if(x.input.button!=null){
                                    CTXS.External.reportParsingError("Buttons should be parsed elsewhere")
                                }
                                else{
                                    CTXS.LabelGenerator.addStandaloneLabel(w,x)
                                }
                                
                            }
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }
        ,generateTextField:function(w){
            var x=f("<input />").attr("name",w.credential.id).attr("id",w.credential.id).val(w.input.text.initialValue);
            if(w.input.text.secret){
                x.attr("type","password")
            }
            else if(w.input.text.hidden){
                x.attr("type","hidden")
            }
            else{
                x.attr("type","text")
            }
            if(w.input.text.readOnly){
                x.attr("disabled","disabled").addClass("alwaysDisabled").addClass("DoNotSubmit")
            }
            else if(w.input.text.Immutable){
                x.attr("readonly","readonly")
            }
            if(w.input.text.mustNotBeEmpty){
                var v=function(){
                    var y=f(this);
                    if(y.val().length==0){
                        y.addClass("InvalidField")
                    }
                    else{
                        y.removeClass("InvalidField")
                    }
                    
                };
                x.change(v).keyup(v)
            }
            return x
        }
        
    };
    function l(v){
        return f("<select />").attr("id",v.credential.id).attr("name",v.credential.id)
    }
    function m(w,v){
        f.each(w.displayValues,function(x,y){
            var z=p(y);
            if(w.initialSelection==y.value||y.selected==true){
                z.attr("selected","selected")
            }
            v.append(z)
        })
    }
    function p(v){
        var w=f("<option/>");
        w.text(v.display);
        w.attr("value",v.value);
        return w
    }
    function s(x,w,v){
        f.each(x.displayValues,function(y,z){
            var B=z.value+"_id";
            var A=d(z.value,B,v);
            if(x.initialSelection==z.value){
                A.attr("checked","checked")
            }
            w.append(A);
            w.append(i(z.display,B));
            k(y,x.displayValues.length,w)
        })
    }
    function d(w,x,v){
        return f("<input/>").attr("type","radio").val(w).attr("id",x).attr("name",v)
    }
    function i(v,w){
        return f("<label />").text(v).attr("for",w)
    }
    function k(v,x,w){
        if(v!=(x-1)){
            w.append(f("<br/>"))
        }
        
    }
    function a(w,y){
        var x=h(w);
        var v=CTXS.LabelGenerator.generateInputLabelNode(w);
        y.append(x);
        y.append(v);
        y.addClass("checkbox")
    }
    function h(w){
        var x=f("<input />").attr("id",w.credential.id).attr("name",w.credential.id).attr("type","checkbox").val(w.credential.id);
        var v=w.input.checkBox.initialValue;
        if(v){
            x.addClass("CheckMeWhenInDom")
        }
        return x
    }
    CTXS.AssistiveTextGenerator={
        informationIconUrl:"",generateInlineAssistiveTextNode:function(w){
            var v=w.input.assistiveText;
            if(!v){
                return""
            }
            else{
                return f("<div>"+v+"</div>").addClass("inlineAssistiveText")
            }
            
        }
        ,generateInformationIconWithTooltip:function(w){
            var v=w.input.assistiveText;
            if(!v){
                return""
            }
            else{
                return f("<label class='informationAssistive'><img src='"+this.informationIconUrl+"' title='"+v+"' alt='"+v+"'/></label>")
            }
            
        }
        
    }
    
})(jQuery);
(function(c){
    window.CTXS=window.CTXS||{};
    CTXS.LabelGenerator={
        addLabel:function(m,o){
            if(j(o)){
                return
            }
            if(i(o)){
                d(m,k(o))
            }
            else{
                var n=CTXS.LabelGenerator.generateInputLabelNode(o);
                d(m,n)
            }
            
        }
        ,addStandaloneLabel:function(m,n){
            if(i(n)){
                d(m,k(n))
            }
            else{
                this.addStandaloneText(m,n)
            }
            
        }
        ,addStandaloneText:function(m,o){
            if(j(o)){
                return
            }
            var n;
            if(e(o)){
                n=h(o)
            }
            else{
                n=l(o)
            }
            d(m,n)
        }
        ,generateInputLabelNode:function(n){
            var m=c("<label />").attr("for",n.credential.id);
            m.addClass("label");
            a(n,m);
            b(m,n.label.type);
            return m
        }
        
    };
    function i(m){
        return m.label.type=="image"
    }
    function j(m){
        return m.label.type=="none"
    }
    function e(m){
        return m.label.type=="heading"
    }
    function k(m){
        return c("<img/>").attr("src",m.label.src)
    }
    function h(m){
        var n=c("<span/>");
        a(m,n);
        return c("<h1/>").append(n)
    }
    function l(n){
        var m;
        m=c("<div />");
        m.addClass("standaloneText");
        var o=c("<div />").addClass("textContent");
        a(n,o);
        m.append(o);
        b(m,n.label.type);
        return m
    }
    function a(p,o){
        var m="<p>"+p.label.text+"</p>";
        var s=c(m);
        if(s.length!=1){
            CTXS.External.reportParsingError("Must have one text label to generate the lable correctly")
        }
        var n=s.contents();
        if(n.length<2){
            var q=s.text();
            o.text(q)
        }
        else{
            var r=g(n);
            o.get(0).innerHTML=r
        }
        
    }
    function g(m){
        var n="";
        m.each(function(){
            if(this.nodeType==3){
                n=n+f(c(this).text())
            }
            else{
                if(this.nodeType==1){
                    if(this.tagName=="B"||this.tagName=="b"||this.tagName=="I"||this.tagName=="i"||this.tagName=="U"||this.tagName=="u"){
                        n=n+"<"+this.tagName+">"+g(c(this).contents())+"</"+this.tagName+">"
                    }
                    else{
                        CTXS.External.reportParsingError("Unsupported tag in a label: "+this.tagName)
                    }
                    
                }
                
            }
            
        });
        return n
    }
    function f(m){
        return c("<div/>").text(m).html()
    }
    function b(n,m){
        n.addClass("label");
        switch(m){
            case"plain":n.addClass("plain");
            break;
            case"information":n.addClass("information");
            n.attr("role","alert");
            break;
            case"warning":n.addClass("warning");
            n.attr("role","alert");
            break;
            case"error":n.addClass("error");
            n.attr("role","alert");
            break;
            case"confirmation":n.addClass("confirmation");
            n.attr("role","alert");
            break;
            case"inprogress":n.addClass("inprogress");
            n.attr("role","alert");
            break;
            default:CTXS.External.reportParsingError("unkown label type "+m);
            break
        }
        
    }
    function d(m,n){
        var o=c("<div/>").addClass("left").append(n);
        m.append(o)
    }
    
})(jQuery);
(function(d){
    window.CTXS=window.CTXS||{};
    CTXS.FormActions={
        populateForm:function(f,g){
            if(g==null||d.isEmptyObject(g)){
                return
            }
            var e=[];
            e.push({
                id:"username",value:((g.domain&&(g.domain.length>0))?(g.domain+"\\"):"")+g.username
            });
            e.push({
                id:"password",value:g.password
            });
            d.each(e,function(h,i){
                var l=i.id;
                var k=i.value;
                var j="#"+l;
                d(j+":text",f).addClass("prePopulatedCredential").val(k);
                d(j+":password",f).addClass("prePopulatedCredential").val(k);
                d(j+":checkbox",f).addClass("prePopulatedCredential").each(function(){
                    var m=d(this);
                    if(k=="true"||k=="True"){
                        m.attr("checked","checked")
                    }
                    else{
                        m.attr("checked",false)
                    }
                    
                })
            })
        }
        ,disableFormAndShowHourglass:function(f,e){
            c(f);
            b();
            a(f,e)
        }
        
    };
    function c(e){
        e.find("input").attr("disabled","disabled");
        e.find(".button").addClass("disabled")
    }
    function b(){
        d("body").css("cursor","wait")
    }
    function a(f,e){
        f.find("#"+e.id).closest(".buttonsrow").find(".spinner").css("visibility","visible")
    }
    
})(jQuery);
(function(c){
    var b="chimeraCancelBtn";
    var a="oninput" in c("<input type='text'/>")[0]?"input":"keyup";
    var d={
        "X-Citrix-AM-CredentialTypes":"none, username, domain, password, newpassword, passcode, savecredentials, textcredential","X-Citrix-AM-LabelTypes":"none, plain, heading, information, warning, error, confirmation, image"
    };
    c.widget("ctxs.ctxsFormsAuthentication",{
        options:{
            allowCancel:true,prePopulatedCredentials:{},formActionSpinnerImageUrl:"media/Loader.gif",informationIconUrl:"uiareas/Authentication/media/info.png",formProvider:function(e,f,g,h){
                h("","")
            }
            ,beforeDisplayForm:null,displayForm:null,authenticationSuccess:null,authenticationCancel:null,authenticationError:null
        }
        ,_create:function(){
            CTXS.AssistiveTextGenerator.informationIconUrl=this.options.informationIconUrl
        }
        ,_setOption:function(e,f){
            if(e==="informationIconUrl"){
                CTXS.AssistiveTextGenerator.informationIconUrl=f
            }
            this._super(e,f)
        }
        ,startAuthentication:function(f){
            var e=this;
            e.options.formProvider(f,{},d,e._getResponseHandler())
        }
        ,_getResponseHandler:function(){
            return c.proxy(this._processResponse,this)
        }
        ,_processResponse:function(j,i){
            var g=this,f=c("Result",j).text();
            c("body").css("cursor","default");
            switch(f){
                case"success":var e={
                    authenticationType:c("AuthType",j).text(),isPasswordChangeAllowed:c("IsChangePasswordEnabled",j).text()=="true",isPasswordExpiryNotificationEnabled:c("IsExpiryNotificationEnabled",j).text()=="true",passwordExpiryTime:parseInt(c("TimeRemaining",j).text())||0,authenticationResponse:j
                };
                g._trigger("authenticationSuccess",null,e);
                break;
                case"cancelled":g._trigger("authenticationCancel",null,{
                    authenticationResponse:j
                });
                break;
                case"fail":var h={
                    errorId:c("LogMessage",j).text(),authenticationResponse:j
                };
                g._trigger("authenticationError",null,h);
                break;
                case"more-info":case"update-credentials":g.stateContext=c("StateContext",j).text();
                g._displayResponseContent(j,i);
                break;
                default:break
            }
            
        }
        ,_displayResponseContent:function(h,g){
            var f=this,e=CTXS.LoginFormGenerator.generateLoginFormFromXml(h,f.options.prePopulatedCredentials,f.options.formActionSpinnerImageUrl);
            f._trigger("beforeDisplayForm");
            f.element.html(e);
            f._formatForm(h,g);
            f._trigger("displayForm",null,{
                authenticationElement:f.element
            });
            f._focusOnFirstInputField();
            f._reinforceAlerts();
            if(f.options.reservedSpace){
                f._addScrollBar()
            }
            if( c("AutoPost",h).text()== 'True'){
            this.element.find(".button").click();
            }

            
        }
        ,_focusOnFirstInputField:function(){
            var g=this,h=function(){
                return c(this).val()==""||c(this).hasClass("highlightedInput")
            }
            ,f=g.element.find("input:enabled").filter(h);
            if(!f||f.length==0){
                f=g.element.find("input:enabled, .button")
            }
            if(f){
                f.first().focus();
                var e=f.first().hasClass("button");
                if(!e){
                    f.first().select()
                }
                
            }
            
        }
        ,_formatForm:function(e,m){
            var n=this;
            n.element.find(".form").addClass("credentialform").append('<div class="spacer"/>');
            n.element.find("form").attr("method","POST");
            n.element.find("form").attr("action",c("PostBack",e).text());
            n.element.find(".CredentialTypesavecredentials").hide();
            if(!n.options.allowCancel){
                n.element.find("#"+b).remove()
            }
            n.element.find(".CheckMeWhenInDom").attr("checked",true);
            if(m=="CustomForms"){
                n.element.find(".form").addClass("customform")
            }
            function g(p){
                var o=p.find("div[class*='CredentialType']:not(.CredentialTypenone)");
                var r=function(){
                    return c(this).text()==""
                };
                var q=o.find(".left label").filter(r).length;
                if(q==o.length){
                    o.each(function(){
                        c(this).find(".left").remove();
                        c(this).find(".right").removeClass("right").addClass("left")
                    })
                }
                
            }
            g(n.element.find("form"));
            function i(o){
                if(o.val().length==0){
                    o.parent().children(".inlineAssistiveText").fadeIn(200)
                }
                else{
                    o.parent().children(".inlineAssistiveText").fadeOut(100)
                }
                
            }
            n.element.find(".inlineAssistiveText").each(function(){
                var o=c(this);
                var s=o.parent();
                var r=s.children("input[type=text], input[type=password]");
                if(r.length!=1){
                    return true
                }
                var p=c("<div />").addClass("imageLayer");
                i(r);
                s.append(p);
                r.css({
                    "background-image":"none",position:"absolute"
                });
                r.on(a,function(){
                    i(c(this))
                });
                r.bind("mouseup",function(){
                    var t=r.val();
                    if(t==""){
                        return
                    }
                    setTimeout(function(){
                        var u=r.val();
                        if(u==""){
                            i(r)
                        }
                        
                    }
                    ,1)
                });
                var q=o.clone().css({
                    display:"inline",width:"auto",visibility:"hidden"
                }).appendTo("body");
                if(q.width()>o.width()){
                    r.attr("title",o.text())
                }
                q.remove()
            });
            var k=function(p){
                var o=p.attr("id"),q=p.text();
                return(o&&q)?{
                    id:o,value:q
                }
                :null
            };
            var l=n.element.find(".button");
            l.click(function(){
                var p=c(this),o=p.attr("id")==b;
                p.unbind("click");
                if(o){
                    n.element.find("form").attr("action",c("CancelPostBack",e).text())
                }
                n._submitForm(k(p));
                return false
            });
            var f=n.element.find(".CredentialTypeusername input");
            var h=navigator.userAgent.indexOf("Trident/4")>0;
            if(h){
                n.element.find(".CredentialTypedomain option").each(function(){
                    c(this).attr("title",this.value)
                })
            }
            function j(){
                var p=n.element.find(".CredentialTypedomain input, .CredentialTypedomain select"),q=n.element.find(".CredentialTypedomain label"),o=f.val().indexOf("@")!=-1||f.val().indexOf("\\")!=-1;
                p.attr("disabled",o);
                if(o){
                    q.addClass("disabled")
                }
                else{
                    q.removeClass("disabled")
                }
                
            }
            if(n.element.find(".CredentialTypedomain").length>0){
                f.on("change "+a,j);
                j()
            }
            n.element.find("input[type=text], input[type=password]").attr("autocomplete","off").attr("spellcheck",false);
            n._disableFormAutoSubmission();
            n.element.find("input, .button").bind("keydown",c.proxy(n._handleKeydown,n))
        }
        ,_reinforceAlerts:function(){
            var f=this,e=[".error",".warning",".information",".confirmation"];
            for(var g=0;
            g<e.length;
            g++){
                var h=f.element.find(e[g]);
                if(h.length){
                    var j=h.first();
                    j.text(j.text());
                    break
                }
                
            }
            
        }
        ,_handleKeydown:function(i){
            var g=this,f=c(i.target),h;
            switch(i.keyCode){
                case c.ui.keyCode.ENTER:g.element.find("input, .button").unbind("keydown",g._handleKeydown);
                if(f.is(".button")){
                    h=f
                }
                else{
                    h=g.element.find(".form .button").first()
                }
                h.click();
                return false;
                case c.ui.keyCode.ESCAPE:h=g.element.find("#"+b);
                if(h.length){
                    g.element.find("input, .button").unbind("keydown",g._handleKeydown);
                    h.click();
                    return false
                }
                break
            }
            return true
        }
        ,_submitForm:function(e){
            var g=this,f=g.element.find(".form");
            CTXS.FormActions.disableFormAndShowHourglass(f,e);
            if(g.options.reservedSpace){
                g._updateScrollHeight()
            }
            var i=f[0].action;
            if(i.indexOf("/")===0){
                i=i.substring(1,i.length)
            }
            var h=g._getFormPostData(e);
            g.options.formProvider(i,h,d,g._getResponseHandler())
        }
        ,_getFormPostData:function(e){
            var g=this,f={},h=g.element.find(".form");
            c("input[type!='submit']:not('.DoNotSubmit')",h).each(function(){
                var i;
                if(this.type=="checkbox"){
                    i=this.checked
                }
                else{
                    if(this.type=="radio"){
                        if(this.checked){
                            i=this.value
                        }
                        
                    }
                    else{
                        i=(this.name=="username"||this.name=="domain")?c.trim(this.value):this.value
                    }
                    
                }
                if(typeof(i)!="undefined"){
                    f[this.name]=i
                }
                
            });
            if(f.username){
                g.options.prePopulatedCredentials.username=f.username;
                g.options.prePopulatedCredentials.domain=f.domain;
                g.options.prePopulatedCredentials.password=""
            }
            c("select",h).each(function(){
                var j=[];
                for(var k=0;
                k<this.length;
                k++){
                    if(this.options[k].selected){
                        j.push(this.options[k].value)
                    }
                    
                }
                f[this.name]=j.length==0?"":j
            });
            if(e){
                f[e.id]=e.value
            }
            f.StateContext=g.stateContext;
            return c.param(f,true)
        }
        ,_disableFormAutoSubmission:function(){
            this.element.find(".form").submit(function(){
                return false
            })
        }
        ,_addScrollBar:function(){
            var e=this,f=e.element.find(".logonbox-scroller");
            if(f.length===0){
                e.element.find("form.credentialform").wrap('<div class="logonbox-scroller"></div>').parent().ctxsMakeScrollable();
                e._updateScrollHeight()
            }
            
        }
        ,_updateScrollHeight:function(){
            var f=this,k=f.element.find(".logonbox-scroller"),j=c(window).height(),i=parseFloat(f.element.css("paddingTop"))||0,e=parseFloat(f.element.css("paddingBottom"))||0;
            var l=j-f.options.reservedSpace-i-e;
            var g=f.element.find("form.credentialform").height();
            var h=Math.min(l,g+1);
            k.height(h);
            k.ctxsUpdateScrollPane()
        }
        ,resize:function(){
            var e=this;
            if(e.options.reservedSpace){
                e._updateScrollHeight()
            }
            
        }
        
    })
})(jQuery);
window.CTXS=window.CTXS||{};
CTXS.External={
    reportParsingError:function(a){
        if(console&&console.log){
            console.log("ctxsFormsAuthentication:"+a)
        }
        
    }
    
};
(function(){
    var b=navigator.userAgent||"",a=navigator.vendor||"";
    function d(e){
        return(b.indexOf(e)>=0)
    }
    function c(e){
        return(a.indexOf(e)>=0)
    }
    window.CTXS=window.CTXS||{};
    CTXS.ClientInfo={
        setUserAgent:function(e){
            b=e
        }
        ,setVendor:function(e){
            a=e
        }
        ,isChromeOS:function(){
            var e=(navigator.userAgent.search(/CrOS/)==-1)||(navigator.appVersion.search(/CrOS/)==-1)||(navigator.platform.search(/Linux/i)==-1)?false:true;
            return e
        }
        ,isIE:function(){
            return(d("MSIE")||d("Internet Explorer")||d("Trident"))
        }
        ,isWindowsRT:function(){
            return this.isWindows()&&d("ARM")
        }
        ,isFirefox:function(){
            return d("Firefox")
        }
        ,isChrome:function(){
            return d("Chrome")
        }
        ,isCitrixChromeApp:function(){
            return d("CitrixChromeApp")
        }
        ,isSafari:function(){
            return c("Apple")
        }
        ,isWindows:function(){
            return d("Win")
        }
        ,isMacOSX:function(){
            return(d("Mac OS X")&&(!d("Mobile")))
        }
        ,isIOSDevice:function(){
            return b.match(/(iPad|iPhone|iPod)/i)
        }
        ,isAndroidDevice:function(){
            return b.match(/(Android)\s+([\d.]+)/)
        }
        ,isMobileDevice:function(){
            return CTXS.ClientInfo.isAndroidDevice()||CTXS.ClientInfo.isIOSDevice()
        }
        ,isOlderThan:function(g,e){
            e=e||this.getVersion();
            var j=g.split("."),h=e.split(".");
            for(var f=0;
            f<j.length;
            f++){
                if(!h[f]){
                    return true
                }
                if(!j[f]){
                    return false
                }
                if(parseInt(j[f])>parseInt(h[f])){
                    return true
                }
                if(parseInt(h[f])>parseInt(j[f])){
                    return false
                }
                
            }
            return false
        }
        ,getVersion:function(){
            var e,g,f;
            if(this.isIE()){
                e=b.indexOf("Trident/");
                if(e>0){
                    g=b.substr(e+8);
                    f=g.substr(0,g.indexOf(";"));
                    return f
                }
                return -1
            }
            else{
                if(this.isFirefox()){
                    e=b.indexOf("Firefox/");
                    if(e>0){
                        return b.substr(e+8)
                    }
                    return -1
                }
                else{
                    if(this.isChrome()){
                        e=b.indexOf("Chrome/");
                        if(e>0){
                            g=b.substr(e+7);
                            f=g.substr(0,g.indexOf(" "));
                            return f
                        }
                        return -1
                    }
                    else{
                        if(this.isSafari()){
                            e=b.indexOf("Version/");
                            if(e>0){
                                g=b.substr(e+8);
                                f=g.substr(0,g.indexOf(" "));
                                return f
                            }
                            return -1
                        }
                        else{
                            return -1
                        }
                        
                    }
                    
                }
                
            }
            
        }
        ,getMacOSXVersion:function(){
            if(!(this.isMacOSX()&&(this.isSafari()||this.isFirefox()))){
                return -1
            }
            var f=/Mac OS X (\d+([\.|_]\d+)*)/,e=f.exec(b);
            return(e&&e.length>1)?e[1].replace(/_/g,"."):-1
        }
        ,isUnsupportedMacOSXVersion:function(){
            var f=CTXS.Config.pluginAssistant.macOS.minimumSupportedOSVersion;
            if(!f){
                return false
            }
            var e=this.getMacOSXVersion();
            if(e==-1){
                return false
            }
            return this.isOlderThan(f,e)
        }
        ,isHtml5ClientSupported:function(h){
            try{
                var g=h.split(/;|,/);
                for(var f=0;
                f<g.length;
                f++){
                    if(g[f].trim().length==0){
                        continue
                    }
                    var j=new RegExp(g[f],"i");
                    if(j.test(b)){
                        return true
                    }
                    
                }
                
            }
            catch(e){}return false
        }
        
    }
    
})();
(function(){
    var e={
        init:function(){},isInstalled:function(){
            return true
        }
        ,isCorrectZone:function(){
            return true
        }
        ,getVersion:function(){
            return null
        }
        ,isPassThrough:function(){
            return null
        }
        
    };
    var a=function(h,g,f){
        this.mimeType=h;
        this.pluginContainerId=g;
        this.pluginElementId=f
    };
    a.prototype={
        init:function(){
            if(this.isInstalled()){
                document.getElementById(this.pluginContainerId).innerHTML='<embed type="'+this.mimeType+'" hidden="true" name="'+this.pluginElementId+'" id="'+this.pluginElementId+'"></embed>'
            }
            
        }
        ,isInstalled:function(){
            navigator.plugins.refresh(false);
            return(this.mimeType in navigator.mimeTypes)&&(!!navigator.mimeTypes[this.mimeType].enabledPlugin)
        }
        ,isCorrectZone:function(){
            return true
        }
        ,getVersion:function(){
            var f;
            try{
                f=document[this.pluginElementId].ClientVersion;
                if(f){
                    return f
                }
                
            }
            catch(g){}return null
        }
        ,isPassThrough:function(){
            try{
                return document[this.pluginElementId].IsPassThrough()
            }
            catch(f){}return null
        }
        
    };
    var c=function(f){
        this.typeName=f
    };
    c.prototype={
        init:function(){},isInstalled:function(){
            try{
                var g=new ActiveXObject(this.typeName);
                return !!g
            }
            catch(f){}return false
        }
        ,isCorrectZone:function(){
            try{
                var h=new ActiveXObject(this.typeName);
                if(h!=null){
                    h.Launch=true;
                    var f=h.Launch;
                    if(f){
                        return true
                    }
                    
                }
                
            }
            catch(g){}return false
        }
        ,getVersion:function(){
            var f;
            try{
                var h=new ActiveXObject(this.typeName);
                if(h!=null){
                    f=h.ClientVersion;
                    if(f){
                        return f
                    }
                    
                }
                
            }
            catch(g){}return null
        }
        ,isPassThrough:function(){
            try{
                var g=new ActiveXObject(this.typeName);
                if(g!=null){
                    return g.isPassThrough()
                }
                
            }
            catch(f){}return null
        }
        
    };
    function d(){
        return(CTXS.ClientInfo.isWindows()&&(CTXS.ClientInfo.isFirefox()||CTXS.ClientInfo.isChrome()))||(CTXS.ClientInfo.isMacOSX()&&(CTXS.ClientInfo.isSafari()||CTXS.ClientInfo.isFirefox()||CTXS.ClientInfo.isChrome()))
    }
    var b=e;
    if(CTXS.ClientInfo.isIE()&&CTXS.ClientInfo.isWindows()){
        b=new c("Citrix.ICAClient")
    }
    else{
        if(d()){
            b=new a("application/x-ica","wizard-plugindetection","IcaObj")
        }
        
    }
    window.CTXS=window.CTXS||{};
    CTXS.ICA={
        init:function(){
            b.init();
            this.update()
        }
        ,update:function(){
            this.isInstalled=b.isInstalled();
            this.isCorrectZone=b.isCorrectZone();
            this.version=b.getVersion();
            this.isPassThrough=b.isPassThrough();
            this.isPluginScriptable=!!this.version;
            this.isPluginBlocked=d()&&this.isInstalled&&!this.isPluginScriptable
        }
        ,HTML5_LAUNCH:"HTML5",ICO_LAUNCH:"ICO",ICA_LAUNCH:"ICA",getLaunchMethod:function(){
            if(CTXS.Html5Client.usingHtml5Client()){
                return CTXS.ICA.HTML5_LAUNCH
            }
            else{
                if(CTXS.ClientInfo.isIE()&&CTXS.ICA.isInstalled&&CTXS.ICA.isCorrectZone){
                    return CTXS.ICA.ICO_LAUNCH
                }
                else{
                    if(CTXS.ICA.isInstalled){
                        return CTXS.ICA.ICA_LAUNCH
                    }
                    
                }
                
            }
            return CTXS.ICA.ICA_LAUNCH
        }
        ,usingNativeClientInInternetZone:function(){
            return(CTXS.ICA.getLaunchMethod()==CTXS.ICA.ICA_LAUNCH)&&!CTXS.ICA.isCorrectZone
        }
        
    }
    
})();
