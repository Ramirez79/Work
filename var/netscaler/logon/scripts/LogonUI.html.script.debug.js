(function(a){
    a.fn.extend({
        ctxsDisplayPane:function(){
            a(".mainpane").hide();
            this.ctxsLocalize().show();
            return this
        }
        ,ctxsWrapAsMessagePane:function(){
            var b=this.html(),c='<div class="ctxsui-messagebox"><div class="messagebox-top"><div class="messagebox-topleft"></div><div class="messagebox-topbody"></div><div class="messagebox-topright"></div></div><div class="messagebox-middle"><div class="messagebox-middleleft"></div><div class="messagebox-middlebody">'+b+'</div><div class="messagebox-middleright"></div></div><div class="messagebox-bottom"><div class="messagebox-bottomleft"></div><div class="messagebox-bottombody"></div><div class="messagebox-bottomright"></div></div></div >';
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
                verticalGutter:0,enableKeyboardNavigation:false
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
            this.on("keyup.escape",".custombutton",function(d){
                if(d.keyCode==a.ui.keyCode.ESCAPE){
                    var c=b.find(".cancelDialogButton .custombutton");
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
        
    });
    window.CTXS=a.extend(window.CTXS||{},{
        button:function(c,d){
            var b='<div class="resizablebutton"><span class="leftdoor"><span class="rightdoor"></span></span></div>';
            return c.each(function(f,g){
                var e=a(g);
                if(e.is("a")){
                    if(d){
                        var h=e.text();
                        e.text("").addClass("leftdoor").append('<span class="rightdoor">'+h+"</span>").wrap('<div class="resizablebutton"></div>')
                    }
                    else{
                        e.wrap(b)
                    }
                    
                }
                else{
                    if(e.is("input")){
                        e.wrap(b).closest(".leftdoor").hover(function(){
                            a(this).addClass("hover")
                        }
                        ,function(){
                            a(this).removeClass("hover")
                        })
                    }
                    
                }
                
            }).closest(".resizablebutton")
        }
        ,displayMessagePane:function(b){
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
            if(e.data("ctxsDialog")){
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
        ,
    })
})(jQuery);
(function(f){
    window.CTXS=window.CTXS||{};
    CTXS.LoginFormGenerator={
        generateLoginFormFromXml:function(i,k){
            var j=f("<form class='form insertPoint' action='' />");
            var h=e(i);
            CTXS.RequirementNodeProcessor.addFieldsForEachRequirement(h,j,i);
            CTXS.LabelHighlighter.highlightFieldsThatNeedHighlightingAndTagInputs(h,j);
            CTXS.FormActions.populateForm(j,k);
            return j
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
        return f("<input />").attr("id",i).attr("name",i).attr("type","submit").attr("value",h)
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
        return f("<input />").attr("id","cancelBtn").attr("name","cancelBtn").attr("type","submit").attr("value",h)
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
        var v=f("InitialValue",B).text();
        var A=f("Constraint",B).text();
        var w=A==".+";
        return{
            secret:x,readOnly:C,initialValue:v,mustNotBeEmpty:w
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
                z.append(y);
                w.append(z)
            }
            else{
                if(x.input.checkBox!=null){
                    a(x,z);
                    w.append(z)
                }
                else{
                    if(x.input.comboBox!=null){
                        CTXS.LabelGenerator.addLabel(w,x);
                        y=l(x);
                        m(x.input.comboBox,y);
                        z.append(y);
                        w.append(z)
                    }
                    else{
                        if(x.input.multiComboBox!=null){
                            CTXS.LabelGenerator.addLabel(w,x);
                            y=l(x).attr("multiple","multiple");
                            m(x.input.multiComboBox,y);
                            z.append(y);
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
                                    CTXS.LabelGenerator.addStandaloneText(w,x)
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
            else{
                x.attr("type","text")
            }
            if(w.input.text.readOnly){
                x.attr("disabled","disabled").addClass("alwaysDisabled").addClass("DoNotSubmit")
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
        generateInlineAssistiveTextNode:function(w){
            var v=w.input.assistiveText;
            if(!v){
                return""
            }
            else{
                return f("<div>"+v+"</div>").addClass("inlineAssistiveText")
            }
            
        }
        
    }
    
})(jQuery);
(function(c){
    window.CTXS=window.CTXS||{};
    CTXS.LabelGenerator={
        addLabel:function(k,m){
            if(i(m)){
                return
            }
            var l=CTXS.LabelGenerator.generateInputLabelNode(m);
            d(k,l)
        }
        ,addStandaloneText:function(k,m){
            if(i(m)){
                return
            }
            var l;
            if(e(m)){
                l=h(m)
            }
            else{
                l=j(m)
            }
            d(k,l)
        }
        ,generateInputLabelNode:function(l){
            var k=c("<label />").attr("for",l.credential.id);
            k.addClass("label");
            a(l,k);
            b(k,l.label.type);
            return k
        }
        
    };
    function i(k){
        return k.label.type=="none"
    }
    function e(k){
        return k.label.type=="heading"
    }
    function h(k){
        var l=c("<span/>");
        a(k,l);
        return c("<h1/>").append(l)
    }
    function j(l){
        var k;
        k=c("<div />");
        k.addClass("standaloneText");
        var m=c("<div />").addClass("textContent");
        a(l,m);
        k.append(m);
        b(k,l.label.type);
        return k
    }
    function a(n,m){
        var k="<p>"+n.label.text+"</p>";
        var q=c(k);
        if(q.length!=1){
            CTXS.External.reportParsingError("Must have one text label to generate the lable correctly")
        }
        var l=q.contents();
        if(l.length<2){
            var o=q.text();
            m.text(o)
        }
        else{
            var p=g(l);
            m.get(0).innerHTML=p
        }
        
    }
    function g(k){
        var l="";
        k.each(function(){
            if(this.nodeType==3){
                l=l+f(c(this).text())
            }
            else{
                if(this.nodeType==1){
                    if(this.tagName=="B"||this.tagName=="b"||this.tagName=="I"||this.tagName=="i"||this.tagName=="U"||this.tagName=="u"){
                        l=l+"<"+this.tagName+">"+g(c(this).contents())+"</"+this.tagName+">"
                    }
                    else{
                        CTXS.External.reportParsingError("Unsupported tag in a label: "+this.tagName)
                    }
                    
                }
                
            }
            
        });
        return l
    }
    function f(k){
        return c("<div/>").text(k).html()
    }
    function b(l,k){
        l.addClass("label");
        switch(k){
            case"plain":l.addClass("plain");
            break;
            case"information":l.addClass("information");
            l.attr("role","alert");
            break;
            case"warning":l.addClass("warning");
            l.attr("role","alert");
            break;
            case"error":l.addClass("error");
            l.attr("role","alert");
            break;
            case"confirmation":l.addClass("confirmation");
            l.attr("role","alert");
            break;
            case"inprogress":l.addClass("inprogress");
            l.attr("role","alert");
            break;
            default:CTXS.External.reportParsingError("unkown label type "+k);
            break
        }
        
    }
    function d(k,l){
        var m=c("<div/>").addClass("left").append(l);
        k.append(m)
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
        e.find("input").attr("disabled","disabled")
    }
    function b(){
        d("body").css("cursor","wait")
    }
    function a(f,e){
        var g=d("<div class='spinner' />").append(d("<img src='"+e+"' alt='' />"));
        f.find(".buttonsrow").append(g)
    }
    
})(jQuery);
(function(a){
    a.widget("ctxs.ctxsFormsAuthentication",{
        options:{
            allowCancel:true,prePopulatedCredentials:{},formActionSpinnerImageUrl:"media/Loader.gif",formProvider:function(b,c,d){
                d("","")
            }
            ,beforeDisplayForm:null,displayForm:null,authenticationSuccess:null,authenticationCancel:null,authenticationError:null
        }
        ,startAuthentication:function(c){
            var b=this;
            b.options.formProvider(c,{},b._getResponseHandler())
        }
        ,_getResponseHandler:function(){
            return a.proxy(this._processResponse,this)
        }
        ,_processResponse:function(f,e){
            var d=this,c=a("Result",f).text();
            a("body").css("cursor","default");
            switch(c){
                case"success":var b={
                    authenticationType:a("AuthType",f).text(),isPasswordChangeAllowed:a("IsChangePasswordEnabled",f).text()=="true",isPasswordExpiryNotificationEnabled:a("IsExpiryNotificationEnabled",f).text()=="true",passwordExpiryTime:parseInt(a("TimeRemaining",f).text())||0
                };
                d._trigger("authenticationSuccess",null,b);
                break;
                case"cancelled":d._trigger("authenticationCancel");
                break;
                case"fail":var g={
                    errorId:a("LogMessage",f).text()
                };
                d._trigger("authenticationError",null,g);
                break;
                case"more-info":case"update-credentials":d.stateContext=a("StateContext",f).text();
                d._displayResponseContent(f,e);
                break;
                default:break
            }
            
        }
        ,_displayResponseContent:function(e,d){
            var c=this,b=CTXS.LoginFormGenerator.generateLoginFormFromXml(e,c.options.prePopulatedCredentials);
            c._trigger("beforeDisplayForm");
            c.element.html(b);
            c._formatForm(e,d);
            c._trigger("displayForm",null,{
                authenticationElement:c.element
            });
            c._focusOnFirstInputField();
            c._reinforceAlerts()
        }
        ,_focusOnFirstInputField:function(){
            var d=this,e=function(){
                return a(this).val()==""||a(this).hasClass("highlightedInput")
            }
            ,c=d.element.find("input:enabled").filter(e);
            if(!c||c.length==0){
                c=d.element.find("input:enabled")
            }
            if(c){
                c.first().focus();
                var b=c.first().hasClass("custombutton");
                if(b){
                    c.first().find(".custombutton").focus()
                }
                else{
                    c.first().select()
                }
                
            }
            
        }
        ,_formatForm:function(g,f){
            var d=this;
            d.element.find(".form").addClass("credentialform").append('<div class="spacer"/>');
            d.element.find("form").attr("method","POST");
            d.element.find("form").attr("action",a("PostBack",g).text());
            d.element.find(".CredentialTypesavecredentials").remove();
            if(!d.options.allowCancel){
                d.element.find("#cancelBtn").remove()
            }
            d.element.find(".CheckMeWhenInDom").attr("checked",true);
            if(f=="CustomForms"){
                d.element.find(".form").addClass("customform")
            }
            function c(j){
                if(j.val().length==0){
                    j.parent().children(".inlineAssistiveText").fadeIn(200)
                }
                else{
                    j.parent().children(".inlineAssistiveText").fadeOut(100)
                }
                
            }
            d.element.find(".inlineAssistiveText").each(function(){
                var j=a(this);
                var n=j.parent();
                var m=n.children("input[type=text], input[type=password]");
                if(m.length!=1){
                    return true
                }
                var k=a("<div />").addClass("imageLayer");
                c(m);
                n.append(k);
                m.css({
                    "background-image":"none",position:"absolute"
                });
                m.keyup(function(){
                    c(a(this))
                });
                m.bind("mouseup",function(){
                    var o=m.val();
                    if(o==""){
                        return
                    }
                    setTimeout(function(){
                        var p=m.val();
                        if(p==""){
                            c(m)
                        }
                        
                    }
                    ,1)
                });
                var l=j.clone().css({
                    display:"inline",width:"auto",visibility:"hidden"
                }).appendTo("body");
                if(l.width()>j.width()){
                    m.attr("title",j.text())
                }
                l.remove()
            });
            var b=function(k){
                var j=k.attr("id"),l=k.attr("value");
                return(j&&l)?{
                    id:j,value:l
                }
                :null
            };
            var h=d.element.find("input[type=submit]");
            h.addClass("custombutton");
            CTXS.button(h).click(function(){
                var l=a(this),j=l.find(".custombutton"),k=j.attr("id")=="cancelBtn";
                l.unbind("click");
                if(k){
                    d.element.find("form").attr("action",a("CancelPostBack",g).text())
                }
                d._submitForm(b(j))
            });
            var i=d.element.find(".CredentialTypeusername input");
            function e(){
                var k=d.element.find(".CredentialTypedomain input, .CredentialTypedomain select"),l=d.element.find(".CredentialTypedomain label"),j=i.val().indexOf("@")!=-1||i.val().indexOf("\\")!=-1;
                k.attr("disabled",j);
                if(j){
                    l.addClass("disabled")
                }
                else{
                    l.removeClass("disabled")
                }
                
            }
            if(d.element.find(".CredentialTypedomain").length>0){
                i.on("change keyup",e);
                e()
            }
            d.element.find("input[type=text], input[type=password]").attr("autocomplete","off").attr("spellcheck",false);
            d._disableFormAutoSubmission();
            d.element.find("input").bind("keydown",a.proxy(d._handleKeydown,d))
        }
        ,_reinforceAlerts:function(){
            var c=this,b=[".error",".warning",".information",".confirmation"];
            for(var d=0;
            d<b.length;
            d++){
                var e=c.element.find(b[d]);
                if(e.length){
                    var f=e.first();
                    f.text(f.text());
                    break
                }
                
            }
            
        }
        ,_handleKeydown:function(f){
            var c=this,b=a(f.target),d;
            switch(f.keyCode){
                case a.ui.keyCode.ENTER:c.element.find("input").unbind("keydown",c._handleKeydown);
                if(b.is(":submit")){
                    d=b
                }
                else{
                    d=c.element.find(".form :submit").first()
                }
                d.click();
                return false;
                case a.ui.keyCode.ESCAPE:d=c.element.find("#cancelBtn");
                if(d.length){
                    c.element.find("input").unbind("keydown",c._handleKeydown);
                    d.click();
                    return false
                }
                break
            }
            return true
        }
        ,_submitForm:function(b){
            var d=this,c=d.element.find(".form");
            CTXS.FormActions.disableFormAndShowHourglass(c,d.options.formActionSpinnerImageUrl);
            var f=c[0].action;
            if(f.indexOf("/")===0){
                f=f.substring(1,f.length)
            }
            var e=d._getFormPostData(b);
            d.options.formProvider(f,e,d._getResponseHandler())
        }
        ,_getFormPostData:function(b){
            var d=this,c={},e=d.element.find(".form");
            a("input[type!='submit']:not('.DoNotSubmit')",e).each(function(){
                c[this.name]=(this.name=="username"||this.name=="domain")?a.trim(this.value):this.value
            });
            if(c.username){
                d.options.prePopulatedCredentials.username=c.username;
                d.options.prePopulatedCredentials.domain=c.domain;
                d.options.prePopulatedCredentials.password=""
            }
            a("select",e).each(function(){
                if(this.selectedIndex>-1){
                    c[this.name]=this.options[this.selectedIndex].value
                }
                
            });
            if(b){
                c[b.id]=b.value
            }
            c.StateContext=d.stateContext;
            return c
        }
        ,_disableFormAutoSubmission:function(){
            this.element.find(".form").submit(function(){
                return false
            })
        }
        ,
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
