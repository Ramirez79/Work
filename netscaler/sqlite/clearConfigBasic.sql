delete from ns_rnat where 1=1;
delete from ns_rnat6 where 1=1;
delete from ns_inat where 1=1;
delete from ns_nat64 where 1=1;
UPDATE ns_inatparam set nsa_nat46prefix=NULL,nsa_nat46_ignoretos="NO",nsa_nat46_udpzerochksum="ENABLED",nsa_nat46_ipv6mtu=1280,nsa_nat46_v6fragheader="ENABLED" where 1=1;
delete from ns_nd6 where 1=1;

delete from expression_metadata where 1=1;
delete from ns_assignment where 1=1;
delete from ns_variable where 1=1;
delete from reference where 1=1;
delete from mapreference where 1=1;
delete from ns_policy where 1=1;
delete from ns_action where 1=1;
delete from custom_header where 1=1;

delete from ns_patset where 1=1;
delete from ns_patset_ns_pattern_binding where 1=1;

delete from ns_stringmap where 1=1;
delete from ns_stringmap_ns_pattern_binding where 1=1;

delete from ns_dataset where 1=1;
delete from ns_dataset_ns_value_binding where 1=1;

delete from ns_http_callout where 1=1;

delete from ns_mapentry where 1=1;

delete from ns_pqpolicy where 1=1;

delete from ns_scpolicy where 1=1;
delete from ns_svc_ns_scpolicy_binding where 1=1;

delete from ns_dospolicy where 1=1;
delete from ns_svc_ns_dospolicy_binding where 1=1;

delete from ns_rewritepolicy where 1=1;
delete from ns_rewriteaction where 1=1;
delete from ns_rewrite_policylabel where 1=1;
delete from ns_rewriteglobal_ns_rewritepolicy_binding where 1=1;
delete from ns_rewrite_policylabel_ns_rewritepolicy_binding where 1=1;

delete from ns_responderpolicy where 1=1;
delete from ns_responderaction where 1=1;
delete from ns_responder_policylabel where 1=1;
delete from ns_responderglobal_ns_responderpolicy_binding where 1=1;
delete from ns_responder_policylabel_ns_responderpolicy_binding where 1=1;

delete from ns_as_policy where 1=1;
delete from ns_as_policylabel where 1=1;
delete from ns_as_global_ns_as_policy_binding where 1=1;
delete from ns_as_global_ns_syslogplcy_binding where 1=1;
delete from ns_as_global_ns_nslogplcy_binding where 1=1;
delete from ns_as_policylabel_ns_as_policy_binding where 1=1;
delete from ns_as_fieldtype where 1=1;
delete from ns_as_cffield where 1=1;
delete from ns_as_profile where 1=1;
delete from ns_as_profile_ns_starturl_binding where 1=1;
delete from ns_as_profile_ns_denyurl_binding where 1=1;
delete from ns_as_profile_ns_formfieldconsistency_binding where 1=1;
delete from ns_as_profile_ns_cookieconsistency_binding where 1=1;
delete from ns_as_profile_ns_csrf_tag_binding where 1=1;
delete from ns_as_profile_ns_safexss_binding where 1=1;
delete from ns_as_profile_ns_safesql_binding where 1=1;
delete from ns_as_profile_ns_fieldformat_binding where 1=1;
delete from ns_as_profile_ns_as_content_type_binding where 1=1;
delete from ns_as_profile_ns_as_exclude_res_content_type_binding where 1=1;
delete from ns_as_profile_ns_as_objectexpression_binding where 1=1;
delete from ns_as_profile_ns_as_trusted_learning_clients_binding where 1=1;
delete from ns_as_profile_ns_xmldos_url_binding where 1=1;
delete from ns_as_profile_ns_xmlwsi_url_binding where 1=1;
delete from ns_as_profile_ns_xml_validation_url_binding where 1=1;
delete from ns_as_profile_ns_xml_attachment_url_binding where 1=1;
delete from ns_as_profile_ns_safexmlsql_binding where 1=1;
delete from ns_as_profile_ns_safexmlxss_binding where 1=1;
delete from ns_as_learningsettings where 1=1;
delete from ns_as_xmlcontenttype where 1=1;

UPDATE ns_as_enginesettings set nsa_default_profile="APPFW_BYPASS",nsa_undefaction="APPFW_BLOCK",nsa_as_enginesettings_sessiontimeout=900,nsa_as_enginesettings_learn_rate_limit=400,nsa_as_enginesettings_sessionlifetime=0,nsa_as_enginesettings_sessioncookiename=null,nsa_as_enginesettings_clientiploggingheader=null,nsa_as_enginesettings_importsizelimit=134217728,nsa_as_enginesettings_signatureautoupdate="OFF",nsa_as_enginesettings_signatureurl="https://s3.amazonaws.com/NSAppFwSignatures/SignaturesMapping.xm",nsa_as_enginesettings_cki_post_encrypt_prefix=null,nsa_as_enginesettings_log_malformed_req="ON",nsa_as_enginesettings_ceflogging="OFF",nsa_entity_decoding="OFF",nsa_as_use_configurable_secret_key="OFF" where 1=1;

delete from ns_transform_policy where 1=1;
delete from ns_transform_profile where 1=1;
delete from ns_transform_action where 1=1;
delete from ns_transform_profile_ns_transform_action_binding where 1=1;
delete from ns_transform_policylabel where 1=1;
delete from ns_transform_bind_global_ns_transform_policy_binding where 1=1;
delete from ns_transform_policylabel_ns_transform_policy_binding where 1=1;

delete from ns_sslpolicy where 1=1;
delete from ns_ssl_action where 1=1;
delete from ns_ssl_policylabel where 1=1;
delete from ns_sslglobal_ns_sslpolicy_binding where 1=1;
delete from ns_ssl_policylabel_ns_sslpolicy_binding where 1=1;
delete from ns_sslservice_ns_sslpolicy_binding where 1=1;
delete from ns_sslvserver_ns_sslpolicy_binding where 1=1;

delete from ns_appflowpolicy where 1=1;
delete from ns_appflowaction where 1=1;
delete from ns_appflow_collector where 1=1;
delete from ns_appflow_policylabel where 1=1;
delete from ns_appflowglobal_ns_appflowpolicy_binding where 1=1;
delete from ns_appflow_policylabel_ns_appflowpolicy_binding where 1=1;

delete from ns_icapolicy where 1=1;
delete from ns_ica_action where 1=1;
delete from ns_ica_accessprofile where 1=1;
delete from ns_icaglobal_ns_icapolicy_binding where 1=1;

delete from ns_filaction where 1=1;
delete from ns_filpolicy where 1=1;
delete from ns_filglobal_ns_filpolicy_binding where 1=1;

delete from ns_nslogact where 1=1;
delete from ns_nslogplcy where 1=1;

delete from ns_syslogact where 1=1;
delete from ns_syslogplcy where 1=1;
delete from ns_preauthaction where 1=1;
delete from ns_preauth_policy where 1=1;
delete from ns_aaauser where 1=1;
delete from ns_aaagroup where 1=1;
delete from ns_certaction where 1=1;
delete from ns_ldapaction where 1=1;
delete from ns_negaction where 1=1;
delete from ns_radiusaction where 1=1;
delete from ns_samlaction where 1=1;
delete from ns_dfaaction where 1=1;
delete from ns_samlidpprofile where 1=1;
delete from ns_webauthaction where 1=1;
delete from ns_oauthaction where 1=1;
delete from ns_loginschema where 1=1;
delete from ns_authnprofile where 1=1;

UPDATE ns_certp set nsa_two_factor="OFF",nsa_extr_user=null,nsa_extr_grp=null where 1=1;

UPDATE ns_ldap set nsa_auth_svrip=null,nsa_auth_svrport=389,nsa_auth_timeout=3,nsa_auth_grpid1=null,nsa_ldap_base=null,nsa_ldap_binddn=null,nsa_ldap_bindpwd=null,nsa_encrypted=null,nsa_ldap_loginid=null,nsa_ldap_searchfilt=null,nsa_auth_grpid=null,nsa_ldap_subattrname=null,nsa_ldap_pampwd=null,nsa_sectype="PLAINTEXT",nsa_svrtype="AAA_LDAP_SERVER_TYPE_DEFAULT",nsa_auth_ssoname_attr=null,nsa_enpwchange="DISABLED",nsa_nestedgrp_extrn="OFF",nsa_maxnesting_level=2,nsa_ldap_grpnameid=null,nsa_ldap_grpsearchattr=null,nsa_ldap_grpsearchsubattr=null,nsa_ldap_grpsearchfilt=null where 1=1;

UPDATE ns_radius set nsa_auth_svrip=null,nsa_auth_svrport=1812,nsa_auth_timeout=3,nsa_auth_grpid1=null,nsa_radkey=null,nsa_encrypted=null,nsa_radnasip=null,nsa_radnasid=null,nsa_radvendorid=null,nsa_radattribtype=null,nsa_radgroupsprefix=null,nsa_radgroupsepar=null,nsa_radpassencoding="pap",nsa_radipvendorid=null,nsa_radipattribtype=null,nsa_radius_accounting=null,nsa_radpwdvendorid=null,nsa_radpwdattribtype=null,nsa_authserv_retry=3 where 1=1;

UPDATE ns_tacacs set nsa_auth_svrip=null,nsa_auth_svrport=49,nsa_auth_timeout=3,nsa_tacacs_secret=null,nsa_encrypted=null,nsa_tacacs_authorization=null,nsa_tacacs_accounting=null,nsa_tacacs_accounting_all=null where 1=1;

UPDATE ns_syslogparams set nsa_auth_svrip=null,nsa_auth_svrport=514,nsa_dateformat="MMDDYYYY",nsa_loglevel="DEFAULT_LOGLEVEL",nsa_logfacility="LOCAL0",nsa_tcplog="NONE",nsa_acllog="DISABLED",nsa_logtimezone="GMT_TIME",nsa_userdeflog="NO",nsa_appflow_export="DISABLED",nsa_builtin=null where 1=1;

UPDATE ns_nslogparams set nsa_auth_svrip=null,nsa_auth_svrport=3023,nsa_dateformat="MMDDYYYY",nsa_loglevel="NONE",nsa_logfacility="LOCAL0",nsa_tcplog="NONE",nsa_acllog="DISABLED",nsa_logtimezone="GMT_TIME",nsa_userdeflog="NO",nsa_appflow_export="DISABLED",nsa_builtin=null where 1=1;

delete from ns_message_action where 1=1;

UPDATE ns_tmparam set nsa_sessto=30,nsa_authaction="NS_ALLOW",nsa_ssoenabled="OFF",nsa_sso_credential_index="PRIMARY",nsa_ssodomain=null,nsa_httponly_cookie="YES",nsa_pers_cookie="OFF",nsa_pers_cookie_validity=null,nsa_builtin=null where 1=1;

delete from ns_tmsessionaction where 1=1;
delete from ns_tmsessionpolicy where 1=1;
delete from ns_tmformssoaction where 1=1;
delete from ns_tmtrafficaction where 1=1;
delete from ns_tmtrafficpolicy where 1=1;

delete from ns_aaa_kcdaccount where 1=1;

delete from ns_authoraction where 1=1;
delete from ns_authorpolicy where 1=1;
delete from ns_auth_policylabel where 1=1;
delete from ns_auth_policylabel_ns_authorpolicy_binding where 1=1;

delete from ns_localplcy where 1=1;
delete from ns_radiusplcy where 1=1;
delete from ns_certplcy where 1=1;

delete from ns_tacacsplcy where nsa_name NOT IN (select nsa_policy from ns_vpnvserver_ns_tacacsplcy_binding UNION select nsa_policy from ns_authnvserver_ns_tacacsplcy_binding UNION select nsa_policyname from ns_systemglobal_ns_tacacsplcy_binding);

delete from ns_tacacsaction where nsa_name NOT IN (select nsa_cpreqaction from ns_tacacsplcy);

delete from ns_expr where nsa_name NOT IN (select nsa_rule from ns_tacacsplcy);


delete from ns_ldapplcy where 1=1;
delete from ns_samlplcy where 1=1;
delete from ns_negplcy where 1=1;
delete from ns_samlplcy where 1=1;
delete from ns_authnplcy where 1=1;
delete from ns_dfaplcy where 1=1;
delete from ns_samlidppolicy where 1=1;
delete from ns_webauthplcy where 1=1;
delete from ns_loginschemaplcy where 1=1;

delete from ns_authn_policylabel where 1=1;
delete from ns_authn_policylabel_ns_authnplcy_binding where 1=1; 

delete from ns_systemglobal_ns_nslogplcy_binding where 1=1;
delete from ns_systemglobal_ns_syslogplcy_binding where 1=1;
delete from ns_systemglobal_ns_localplcy_binding where 1=1;
delete from ns_systemglobal_ns_radiusplcy_binding where 1=1;
delete from ns_systemglobal_ns_ldapplcy_binding where 1=1;

delete from ns_aaabindglobal_ns_nslogplcy_binding where 1=1;
delete from ns_aaabindglobal_ns_preauth_policy_binding where 1=1;
delete from ns_aaabindglobal_ns_syslogplcy_binding where 1=1;
delete from ns_aaabindglobal_ns_tmsessionpolicy_binding where 1=1;

delete from ns_aaagroup where 1=1;
delete from ns_aaagroup_ns_aaauser_binding where 1=1;
delete from ns_aaagroup_ns_authorpolicy_binding where 1=1;
delete from ns_aaagroup_ns_intranetip_binding where 1=1;
delete from ns_aaagroup_ns_nslogplcy_binding where 1=1;
delete from ns_aaagroup_ns_syslogplcy_binding where 1=1;
delete from ns_aaagroup_ns_tmsessionpolicy_binding where 1=1;
delete from ns_aaagroup_ns_vpnapplication_binding where 1=1;
delete from ns_aaagroup_ns_vpnsessionpolicy_binding where 1=1;
delete from ns_aaagroup_ns_vpntrafficpolicy_binding where 1=1;
delete from ns_aaagroup_ns_vpnurl_binding where 1=1;

UPDATE ns_aaaparam set nsa_staticpage_caching_state="YES",nsa_defaultauthtype="LOCAL",nsa_maxaaausers=null,nsa_maxloginattempt=null,nsa_faillogintimeout=null,nsa_aaadnatip=null,_nsa_setvs_type=null,nsa_aaa_loglevel="INFORMATIONAL" where 1=1;

delete from ns_aaauser where 1=1;
delete from ns_aaauser_ns_authorpolicy_binding where 1=1;
delete from ns_aaauser_ns_intranetip_binding where 1=1;
delete from ns_aaauser_ns_nslogplcy_binding where 1=1;
delete from ns_aaauser_ns_syslogplcy_binding where 1=1;
delete from ns_aaauser_ns_tmsessionpolicy_binding where 1=1;
delete from ns_aaauser_ns_vpnapplication_binding where 1=1;
delete from ns_aaauser_ns_vpnsessionpolicy_binding where 1=1;
delete from ns_aaauser_ns_vpntrafficpolicy_binding where 1=1;
delete from ns_aaauser_ns_vpnurl_binding where 1=1;

delete from ns_aaa_kcdaccount where 1=1;

delete from ns_tmglobal_ns_nslogplcy_binding where 1=1;
delete from ns_tmglobal_ns_syslogplcy_binding where 1=1;
delete from ns_tmglobal_ns_tmsessionpolicy_binding where 1=1;
delete from ns_tmglobal_ns_tmtrafficpolicy_binding where 1=1;
delete from ns_authnvserver_ns_tmsessionpolicy_binding where 1=1;
delete from ns_authnvserver_ns_certplcy_binding where 1=1;
delete from ns_authnvserver_ns_ldapplcy_binding where 1=1;
delete from ns_authnvserver_ns_localplcy_binding where 1=1;
delete from ns_authnvserver_ns_negplcy_binding where 1=1;
delete from ns_authnvserver_ns_radiusplcy_binding where 1=1;
delete from ns_authnvserver_ns_samlplcy_binding where 1=1;
delete from ns_authnvserver_ns_nslogplcy_binding where 1=1;
delete from ns_authnvserver_ns_syslogplcy_binding where 1=1;
delete from ns_authnvserver_ns_vpnportaltheme_binding where 1=1;
delete from ns_authnvserver_ns_authnplcy_binding where 1=1;
delete from ns_authnvserver_ns_loginschemaplcy_binding where 1=1;
delete from ns_authnvserver_ns_cspolicy_binding where 1=1;
delete from ns_authnvserver_ns_samlidppolicy_binding where 1=1;
delete from ns_authnvserver_ns_webauthplcy_binding where 1=1;
delete from ns_authnvserver where 1=1;
delete from ns_vpnvserver where 1=1;

delete from ns_vpnvserver where 1=1;
delete from ns_vpnvserver_ns_vpnurl_binding where 1=1;
delete from ns_vpnvserver_ns_vpndoublehopserver_binding where 1=1;
delete from ns_vpnvserver_ns_vpnapplication_binding where 1=1;
delete from ns_vpnvserver_ns_intranetip_binding where 1=1;
delete from ns_vpnvserver_ns_syslogplcy_binding where 1=1;
delete from ns_vpnvserver_ns_nslogplcy_binding where 1=1;
delete from ns_vpnvserver_ns_vpnsessionpolicy_binding where 1=1;
delete from ns_vpnvserver_ns_radiusplcy_binding where 1=1;
delete from ns_vpnvserver_ns_ldapplcy_binding where 1=1;
delete from ns_vpnvserver_ns_certplcy_binding where 1=1;
delete from ns_vpnvserver_ns_localplcy_binding where 1=1;
delete from ns_vpnvserver_ns_samlplcy_binding where 1=1;
delete from ns_vpnvserver_ns_vpntrafficpolicy_binding where 1=1;
delete from ns_vpnvserver_ns_preauth_policy_binding where 1=1;
delete from ns_vpnvserver_ns_staserver_binding where 1=1;
delete from ns_vpnvserver_ns_appc_server_binding where 1=1;
delete from ns_vpnvserver_ns_sharefile_server_binding where 1=1;
delete from ns_vpnvserver_ns_vpnclientlessrwpolicy_binding where 1=1;
delete from ns_vpnvserver_ns_rewritepolicy_binding where 1=1;
delete from ns_vpnvserver_ns_responderpolicy_binding where 1=1;
delete from ns_vpnvserver_ns_cachepolicy_binding where 1=1;
delete from ns_vpnvserver_ns_appflowpolicy_binding where 1=1;
delete from ns_vpnvserver_ns_icapolicy_binding where 1=1;
delete from ns_vpnvserver_ns_vpnportaltheme_binding where 1=1;
delete from ns_vpnvserver_ns_vpneula_binding where 1=1;
delete from ns_vpnvserver_ns_dfaplcy_binding where 1=1;

delete from ns_vpnapplication where 1=1;
delete from ns_vpndoublehopserver where 1=1;
delete from ns_vpntrafficpolicy where 1=1;
delete from ns_vpntrafficaction where 1=1;
delete from ns_vpnformssoaction where 1=1;
delete from ns_vpnsamlssoprofile where 1=1;
delete from ns_vpnurl where 1=1;
delete from ns_vpnsessionpolicy where 1=1;
delete from ns_vpnsessionaction where 1=1;
delete from ns_vpnclientlessrwpolicy where 1=1;
delete from ns_vpnclientlessrwprofile where 1=1;
delete from ns_vpnportaltheme where 1=1;
delete from ns_vpneula where 1=1;
delete from ns_vpnglobal where 1=1;
delete from ns_vpnglobal_ns_dmn_binding where 1=1;
delete from ns_vpnglobal_ns_vpnapplication_binding where 1=1;
delete from ns_vpnglobal_ns_vpndoublehopserver_binding where 1=1;
delete from ns_vpnglobal_ns_vpnurl_binding where 1=1;
delete from ns_vpnglobal_ns_syslogplcy_binding where 1=1;
delete from ns_vpnglobal_ns_nslogplcy_binding where 1=1;
delete from ns_vpnglobal_ns_vpnsessionpolicy_binding where 1=1;
delete from ns_vpnglobal_ns_radiusplcy_binding where 1=1;
delete from ns_vpnglobal_ns_ldapplcy_binding where 1=1;
delete from ns_vpnglobal_ns_certplcy_binding where 1=1;
delete from ns_vpnglobal_ns_tacacsplcy_binding where 1=1;
delete from ns_vpnglobal_ns_localplcy_binding where 1=1;
delete from ns_vpnglobal_ns_samlplcy_binding where 1=1;
delete from ns_vpnglobal_ns_vpntrafficpolicy_binding where 1=1;
delete from ns_vpnglobal_ns_intranetip_binding where 1=1;
delete from ns_vpnglobal_ns_staserver_binding where 1=1;
delete from ns_vpnglobal_ns_appc_server_binding where 1=1;
delete from ns_vpnglobal_ns_sharefile_server_binding where 1=1;
delete from ns_vpnglobal_ns_vpnclientlessrwpolicy_binding where 1=1;
delete from ns_vpnglobal_ns_preauth_policy_binding where 1=1;

UPDATE ns_preauth_param set nsa_preauth_action=null,nsa_rule=null,nsa_preauth_kill=null,nsa_preauth_files=null,nsa_builtin=null where 1=1;

delete from ns_cmppolicy where 1=1;
delete from ns_cmpaction where 1=1;
delete from ns_cmp_policylabel where 1=1;
delete from ns_cmpglobal_ns_cmppolicy_binding where 1=1;
delete from ns_cmp_policylabel_ns_cmppolicy_binding where 1=1;

delete from ns_appqospolicy where 1=1;
delete from ns_appqosaction where 1=1;

delete from ns_spilloverpolicy where 1=1;
delete from ns_spilloveraction where 1=1;

delete from ns_copolicy where 1=1;
delete from ns_coaction where 1=1;
delete from ns_coglobal_ns_copolicy_binding where 1=1;

UPDATE ns_coparam set nsa_cache_maxage=30,nsa_img_type="NSCO_IMGTYPE_ALL",nsa_jpeg_quality=100,nsa_inline_css_thres=1024,nsa_inline_js_thres=1024,nsa_inline_img_thres1024 where 1=1;

delete from ns_dns_profile where 1=1;
delete from ns_dnspolicy where 1=1;
delete from ns_dnsaction where 1=1;
delete from ns_dns_policylabel where 1=1;
delete from ns_dnsglobal_ns_dnspolicy_binding where 1=1;
delete from ns_dns_policylabel_ns_dnspolicy_binding where 1=1;
delete from ns_dns_suffix where 1=1;
delete from ns_adns_aaaarec where 1=1;
delete from ns_adns_addrec where 1=1;
delete from ns_adns_cnamerec where 1=1;
delete from ns_adns_mxrec where 1=1;
delete from ns_adns_nsrec where 1=1;
delete from ns_adns_ptrrec where 1=1;
delete from ns_adns_soarec where 1=1;
delete from ns_adns_srvrec where 1=1;
delete from ns_adns_txtrec where 1=1;
delete from ns_dns_view where 1=1;
delete from ns_dns_view_ns_dnspolicy_binding where 1=1;
delete from ns_dns_zone where 1=1;
delete from ns_dns_zone_ns_dmn_binding where 1=1;
delete from ns_gslbservice_ns_dns_view_binding where 1=1;

UPDATE ns_dnsparams set nsa_timeout=null,nsa_retries=5,nsa_minttl=null,nsa_maxttl=604800,nsa_reslvorder="NS_WINSFIRST",nsa_recursion="DISABLED",nsa_resolveorder="NS_FOUR",nsa_dnssec="ENABLED",nsa_max_pipeline=null,nsa_dns_root_referral="DISABLED" where 1=1;
UPDATE ns_systemparameters set nsa_rbaonresponse='ENABLED', nsa_prompt='', nsa_natpcb_forceflush_connlimit=2147483647, nsa_natpcb_rst_on_timeout='DISABLED', nsa_timeout=900, nsa_maxclient=20, nsa_localauthfirst='ENABLED', nsa_restricted_timeout='DISABLED', nsa_strongpassword='disabled', nsa_minpasswordlen=1, nsa_doppler="0", nsa_google_analytics="DISABLED", nsa_cli_loglevel="INFORMATIONAL" where 1=1;

delete from ns_lbvserver where 1=1;
delete from ns_sslvserver where 1=1;
delete from ns_ip WHERE nsa_type = 'VIP' AND (NOT(nsa_cfgflags & 32));
delete from ns_ip6 WHERE nsa_type = 'VIP' AND (NOT(nsa_cfgflags & 32));
UPDATE ns_ip SET nsa_iprefcount=1 WHERE nsa_type = 'VIP' AND (nsa_cfgflags & 32);
UPDATE ns_ip6 SET nsa_iprefcount=1 WHERE nsa_type = 'VIP' AND (nsa_cfgflags & 32);
delete from ns_monitor where 1=1;

delete from ns_manager where 1=1;
delete from ns_trap where 1=1;
delete from ns_trap_ns_snmpuser_binding where 1=1;
delete from ns_snmpuser where 1=1;
delete from ns_snmpgroup where 1=1;
delete from ns_view where 1=1;
delete from ns_community where 1=1;
update ns_snmpengid set nsa_engineid="" where 1=1;
update ns_alarm set nsa_thresholdval=null where 1=1;
update ns_alarm set nsa_normalval=null where 1=1;
update ns_alarm set nsa_state='ENABLED' where 1=1;
update ns_alarm set nsa_severity=null where 1=1;
update ns_alarm set nsa_log='ENABLED' where 1=1;
update ns_alarm set nsa_time=nsa_timeout where 1=1;

delete from ns_crvserver_ns_appflowpolicy_binding where 1=1;
delete from ns_crvserver_ns_icapolicy_binding where 1=1;
delete from ns_crvserver_ns_as_policy_binding where 1=1;
delete from ns_crvserver_ns_cachepolicy_binding where 1=1;
delete from ns_crvserver_ns_responderpolicy_binding where 1=1;
delete from ns_crvserver_ns_rewritepolicy_binding where 1=1;
delete from ns_crvserver_ns_spilloverpolicy_binding where 1=1;
delete from ns_crvserver_ns_copolicy_binding where 1=1;
delete from ns_crvserver_ns_crpolicy_binding where 1=1;
delete from ns_crvserver_ns_cspolicy_binding where 1=1;
delete from ns_crvserver_ns_mapentry_binding where 1=1;
delete from ns_crvserver_ns_filpolicy_binding where 1=1;
delete from ns_crvserver_ns_cmppolicy_binding where 1=1;
delete from ns_crvserver_ns_appqospolicy_binding where 1=1;
delete from ns_crvserver_ns_lbvserver_binding where 1=1;
delete from ns_crpolicy where 1=1;
delete from ns_craction where 1=1;
delete from ns_crvserver where 1=1;                                    


delete from ns_csvserver_ns_appflowpolicy_binding where 1=1;            
delete from ns_csvserver_ns_as_policy_binding where 1=1;              
delete from ns_csvserver_ns_authorpolicy_binding where 1=1;                
delete from ns_csvserver_ns_cachepolicy_binding where 1=1;               
delete from ns_csvserver_ns_cmppolicy_binding where 1=1;
delete from ns_csvserver_ns_cspolicy_binding where 1=1;                  
delete from ns_csvserver_ns_filpolicy_binding where 1=1;                   
delete from ns_csvserver_ns_lbvserver_binding where 1=1;                  
delete from ns_csvserver_ns_nslogplcy_binding where 1=1;                  
delete from ns_csvserver_ns_responderpolicy_binding where 1=1;             
delete from ns_csvserver_ns_rewritepolicy_binding where 1=1;            
delete from ns_csvserver_ns_syslogplcy_binding where 1=1;              
delete from ns_csvserver_ns_tmtrafficpolicy_binding where 1=1;             
delete from ns_csvserver_ns_transform_policy_binding where 1=1;
delete from ns_csvserver_ns_spilloverpolicy_binding where 1=1;
delete from ns_csvserver_ns_copolicy_binding where 1=1;
delete from ns_csvserver_ns_appqospolicy_binding where 1=1;
delete from ns_cs_policylabel_ns_cspolicy_binding where 1=1;
delete from ns_cs_policylabel_ns_lbvserver_binding where 1=1;
delete from ns_cs_policylabel where 1=1;
delete from ns_cspolicy where 1=1;
delete from ns_csaction where 1=1;
delete from ns_csvserver where 1=1;
UPDATE ns_csparam set nsa_stateupdate="DISABLED" where 1=1;

delete from ns_nameserver where 1=1;
delete from ns_ip WHERE nsa_type = 'ADNSsvcIP' AND (NOT(nsa_cfgflags & 32));
delete from ns_ip6 WHERE nsa_type = 'ADNSsvcIP' AND (NOT(nsa_cfgflags & 32));
UPDATE ns_ip SET nsa_ssh=0 WHERE nsa_type = 'ADNSsvcIP';
UPDATE ns_ip6 SET nsa_ssh=0 WHERE nsa_type = 'ADNSsvcIP';
UPDATE ns_ip SET nsa_iprefcount=1, nsa_cfgflags=(nsa_cfgflags - 64) WHERE (nsa_cfgflags & 64) AND (nsa_cfgflags & 32) AND (nsa_cfgflags & (2|4|16));
UPDATE ns_ip SET nsa_iprefcount=1 WHERE ((nsa_cfgflags & 64) AND (nsa_cfgflags & 32)) AND NOT ((nsa_cfgflags & 64) AND (nsa_cfgflags & 32) and (nsa_cfgflags & (2|4|16)));
UPDATE ns_ip6 SET nsa_iprefcount=1, nsa_cfgflags=(nsa_cfgflags - 64) WHERE (nsa_cfgflags & 64) AND (nsa_cfgflags & 32) AND (nsa_cfgflags & (4|16));
UPDATE ns_ip6 SET nsa_iprefcount=1 WHERE ((nsa_cfgflags & 64) AND (nsa_cfgflags & 32)) AND NOT ((nsa_cfgflags & 64) AND (nsa_cfgflags & 32) and (nsa_cfgflags & (4|16)));

UPDATE ns_ip SET nsa_iprefcount=1, nsa_cfgflags=(nsa_cfgflags - 16) WHERE (nsa_cfgflags & 16) AND (nsa_cfgflags & 32) AND (nsa_cfgflags & (2|4|64));
UPDATE ns_ip SET nsa_iprefcount=1 WHERE ((nsa_cfgflags & 16) AND (nsa_cfgflags & 32)) AND NOT ((nsa_cfgflags & 16) AND (nsa_cfgflags & 32) and (nsa_cfgflags & (2|4|64)));
UPDATE ns_ip6 SET nsa_iprefcount=1, nsa_cfgflags=(nsa_cfgflags - 16) WHERE (nsa_cfgflags & 16) AND (nsa_cfgflags & 32) AND ((nsa_cfgflags & 64) OR (nsa_type='SNIP'));
UPDATE ns_ip6 SET nsa_iprefcount=1 WHERE ((nsa_cfgflags & 16) AND (nsa_cfgflags & 32)) AND NOT ((nsa_cfgflags & 16) AND (nsa_cfgflags & 32) and ((nsa_cfgflags & 64) OR (nsa_type='SNIP')));

delete from ns_metrictable where 1=1;
delete from ns_svc where nsa_svc_cfgflags!=9;
delete from ns_lbvserver_ns_appflowpolicy_binding where 1=1;
delete from ns_lbvserver_ns_as_policy_binding where 1=1;                   
delete from ns_lbvserver_ns_authorpolicy_binding where 1=1;                
delete from ns_lbvserver_ns_cachepolicy_binding where 1=1;                 
delete from ns_lbvserver_ns_cmppolicy_binding where 1=1;                   
delete from ns_lbvserver_ns_csvserver_binding where 1=1;                   
delete from ns_lbvserver_ns_filpolicy_binding where 1=1;                   
delete from ns_lbvserver_ns_nslogplcy_binding where 1=1;                   
delete from ns_lbvserver_ns_pqpolicy_binding where 1=1;                    
delete from ns_lbvserver_ns_responderpolicy_binding where 1=1;             
delete from ns_lbvserver_ns_rewritepolicy_binding where 1=1;               
delete from ns_lbvserver_ns_scpolicy_binding where 1=1;                    
delete from ns_lbvserver_ns_svc_binding where 1=1;                         
delete from ns_lbvserver_ns_svc_group_binding where 1=1;                   
delete from ns_lbvserver_ns_syslogplcy_binding where 1=1;                  
delete from ns_lbvserver_ns_tmtrafficpolicy_binding where 1=1;             
delete from ns_lbvserver_ns_transform_policy_binding where 1=1;				
delete from ns_lbvserver_ns_appqospolicy_binding where 1=1;
delete from ns_lbvserver_ns_spilloverpolicy_binding where 1=1;
delete from ns_lbvserver_ns_copolicy_binding where 1=1;                   
delete from ns_lbvserver_ns_capolicy_binding where 1=1;                   
delete from ns_server where 1=1;
delete from ns_svc_group where 1=1;                                        
delete from ns_svc_group_ns_monitor_binding where 1=1;                     
delete from ns_svc_group_ns_svc_group_entity_binding where 1=1;                   
delete from ns_svc_ns_monitor_binding where 1=1;
delete from ns_server_ns_svc_binding where 1=1;
delete from ns_server_ns_svc_group_binding where 1=1;
delete from ns_server_ns_gslbservice_binding where 1=1;

UPDATE ns_netprofile SET nsa_netprofrefcount=0 where 1=1;

delete from ns_capolicy where 1=1; 
delete from ns_caaction where 1=1;
delete from ns_caglobal_ns_capolicy_binding where 1=1;

delete from ns_contentgroup where 1=1;
delete from ns_cache_selector where 1=1;
delete from ns_cache_policylabel where 1=1;
delete from ns_cachepolicy where 1=1; 
delete from ns_cachefwpxy where 1=1; 
delete from ns_cacheglobal_ns_cachepolicy_binding where 1=1;
delete from ns_cache_policylabel_ns_cachepolicy_binding where 1=1; 
delete from ns_extacl where 1=1;
delete from ns_acl6 where 1=1;
delete from ns_pbr where 1=1;
delete from ns_pbr6 where 1=1;
delete from ns_sacl where 1=1;  
delete from ns_sacl6 where 1=1;
delete from ns_tcp_profile where 1=1;
delete from ns_http_profile where 1=1;
delete from ns_group where 1=1;
delete from ns_group_ns_lbvserver_binding where 1=1;
delete from ns_metrictable_ns_metric_binding where 1=1;
delete from ns_monitor_ns_metric_binding where 1=1;

UPDATE ns_html_injection_prebody set nsa_prebody=null where 1=1;

UPDATE ns_html_injection_postbody set nsa_postbody=null where 1=1;

delete from ns_xml_namespace where 1=1;

delete from ns_certlink where 1=1;
delete from ns_certkey where nsa_certkey!="ns-server-certificate";
delete from ns_sslservice where nsa_svcname not in (select nsa_name from ns_svc);
update ns_sslservice set nsa_sessreuse='DISABLED',nsa_cipherredirect='DISABLED',nsa_sslv2redirect='DISABLED',nsa_clrtxtport=0,nsa_svrauth='DISABLED',nsa_sslredirect='DISABLED',nsa_nonfipsciphers='DISABLED',nsa_snienable='DISABLED',nsa_sslcn='YES',nsa_ssl2='DISABLED',nsa_ssl3='ENABLED',nsa_tls1='ENABLED',nsa_tls11='ENABLED',nsa_tls12='ENABLED' where 1=1;
delete from ns_sslsvcgroup where 1=1;
delete from ns_ocsp where 1=1;
delete from ns_crl where 1=1;
delete from ns_cipher where nsa_peflags=4;
delete from ns_sslvserver_ns_certkey_binding where nsa_certkey!="ns-server-certificate";
delete from ns_certkey_ns_sslvserver_binding where nsa_certkey!="ns-server-certificate";
delete from ns_certkey_ns_svc_binding where nsa_certkey!="ns-server-certificate";
delete from ns_sslservice_ns_certkey_binding where nsa_certkey!="ns-server-certificate";

delete from ns_dbuser where 1=1;
delete from ns_forwardsession where 1=1;


delete from ns_certkey_ns_ocsp_binding where 1=1;
delete from ns_cs_policylabel_ns_lbvserver_binding where 1=1;
delete from ns_ssl_policylabel_ns_sslpolicy_binding where 1=1;
delete from ns_sslpolicy_ns_csvserver_binding where 1=1;
delete from ns_sslpolicy_ns_lbvserver_binding where 1=1;
delete from ns_sslservice_ns_cipher_binding where 1=1;
delete from ns_sslservice_ns_ciphersuite_binding where 1=1;
delete from ns_sslservice_ns_dospolicy_binding where 1=1;
delete from ns_sslservice_ns_scpolicy_binding where 1=1;
delete from ns_sslservice_ns_ecc_curve_binding where 1=1;
delete from ns_sslsvcgroup_ns_certkey_binding where 1=1;
delete from ns_sslsvcgroup_ns_cipher_binding where 1=1;
delete from ns_sslsvcgroup_ns_ciphersuite_binding where 1=1;
delete from ns_sslvserver_ns_as_policy_binding where 1=1;
delete from ns_sslvserver_ns_authorpolicy_binding where 1=1;
delete from ns_sslvserver_ns_cachepolicy_binding where 1=1;
delete from ns_sslvserver_ns_cipher_binding where 1=1;
delete from ns_sslvserver_ns_cmppolicy_binding where 1=1;
delete from ns_sslvserver_ns_filpolicy_binding where 1=1;
delete from ns_sslvserver_ns_nslogplcy_binding where 1=1;
delete from ns_sslvserver_ns_pqpolicy_binding where 1=1;
delete from ns_sslvserver_ns_responderpolicy_binding where 1=1;
delete from ns_sslvserver_ns_rewritepolicy_binding where 1=1;
delete from ns_sslvserver_ns_scpolicy_binding where 1=1;
delete from ns_sslvserver_ns_ecc_curve_binding where 1=1;
delete from ns_cipher_ns_svc_binding where 1=1;
delete from ns_cipher_ns_svc_group_binding where 1=1;
delete from ns_cipher_ns_ciphersuite_binding where nsa_peflags=4;
delete from ns_sslvserver_ns_ciphersuite_binding where 1=1;

delete from ns_rpcnode where nsa_ip IN (select nsa_publicip from ns_gslbsite);
delete from ns_gslbaction where 1=1;
delete from ns_gslbpolicy where 1=1;
delete from ns_gslbservice where 1=1;
delete from ns_gslbservice_ns_dns_view_binding where 1=1;
delete from ns_gslbservice_ns_monitor_binding where 1=1;
delete from ns_gslbsite where 1=1;
delete from ns_gslbsite_ns_gslbservice_binding where 1=1;
delete from ns_gslbvserver where 1=1;
delete from ns_gslbvserver_ns_dmn_binding where 1=1;
delete from ns_gslbvserver_ns_gslbservice_binding where 1=1;
delete from ns_gslbvserver_ns_spilloverpolicy_binding where 1=1;

delete from ns_nodegroup where NOT ((nsa_cfgflags & 3) OR (nsa_bounded_cluster_config_entities > 0));
delete from ns_nodegroup_ns_clnode_binding where nsa_name NOT IN (select nsa_name from ns_nodegroup);
delete from ns_nodegroup_ns_lbvserver_binding where 1=1;
delete from ns_nodegroup_ns_csvserver_binding where 1=1;
delete from ns_nodegroup_ns_crvserver_binding where 1=1;
delete from ns_nodegroup_ns_rate_lmt_identifier_binding where 1=1;
delete from ns_nodegroup_ns_identifier_binding where 1=1;
delete from ns_nodegroup_ns_gslbvserver_binding where 1=1;
delete from ns_nodegroup_ns_gslbsite_binding where 1=1;
delete from ns_nodegroup_ns_svc_binding where 1=1;

update ns_diameterparam set nsa_diameter_server_close_propagation="NO" where 1=1;
delete from ns_selector where 1=1;
delete from ns_identifier where 1=1;
delete from ns_rate_lmt_identifier where 1=1;

UPDATE ns_param set nsa_useprxyport="ENABLED" where 1=1;

delete from ns_location where 1=1;
UPDATE ns_locationfile SET nsa_location_file="", nsa_format="netscaler", nsa_data="internal" where 1=1;

delete from ns_tunnelpolicy where 1=1;
delete from ns_tunnelglobal_ns_tunnelpolicy_binding where 1=1;
delete from ns_dtls_profile where 1=1;
UPDATE ns_ssl_param set nsa_sslquantum=8192,nsa_sslcrlmem=256,nsa_sslstrict="NO",nsa_ssltrigger=100,nsa_sslcn="YES",nsa_sslpktcount=45,nsa_sslreneg="NO",nsa_insertion_encoding="Unicode",nsa_ocspcachesize=10, nsa_push_flag=0, nsa_sslstricthost="NO",nsa_push_enc_trigger_timer=1,nsa_cryptodevdisablelimit=0,nsa_undefaction_sslctl="CLIENTAUTH",nsa_undefaction_ssldata="NOOP" where 1=1;
delete from ns_ssl_profile where 1=1;
UPDATE ns_gslbparams set nsa_context='geographic', nsa_q1_label='Continent', nsa_q2_label='Country', nsa_q3_label='Region', nsa_q4_label='City', nsa_q5_label='ISP', nsa_q6_label='Organization', nsa_ldns_to=180, nsa_me_intval=0, nsa_rtttolerance=5, nsa_ldnsmask='255.255.255.255', nsa_v6ldnsmask_prefixlen=128, nsa_ldnsprobeorder='PING DNS TCP', nsa_drop_ldns_reqs='DISABLED' where 1=1;

delete from ns_nodegroup_ns_vpnvserver_binding where 1=1;
delete from ns_vpnapplication where 1=1;
delete from ns_vpnclientlessrwpolicy where 1=1;
delete from ns_vpnclientlessrwprofile where 1=1;
delete from ns_vpndoublehopserver where 1=1;
delete from ns_vpnformssoaction where 1=1;
delete from ns_vpnglobal where 1=1;
delete from ns_vpnglobal_ns_appc_server_binding where 1=1;
delete from ns_vpnglobal_ns_certplcy_binding where 1=1;
delete from ns_vpnglobal_ns_dmn_binding where 1=1;
delete from ns_vpnglobal_ns_intranetip_binding where 1=1;
delete from ns_vpnglobal_ns_ldapplcy_binding where 1=1;
delete from ns_vpnglobal_ns_localplcy_binding where 1=1;
delete from ns_vpnglobal_ns_nslogplcy_binding where 1=1;
delete from ns_vpnglobal_ns_preauth_policy_binding where 1=1;
delete from ns_vpnglobal_ns_radiusplcy_binding where 1=1;
delete from ns_vpnglobal_ns_samlplcy_binding where 1=1;
delete from ns_vpnglobal_ns_sharefile_server_binding where 1=1;
delete from ns_vpnglobal_ns_staserver_binding where 1=1;
delete from ns_vpnglobal_ns_syslogplcy_binding where 1=1;
delete from ns_vpnglobal_ns_tacacsplcy_binding where 1=1;
delete from ns_vpnglobal_ns_vpnapplication_binding where 1=1;
delete from ns_vpnglobal_ns_vpnclientlessrwpolicy_binding where 1=1;
delete from ns_vpnglobal_ns_vpndoublehopserver_binding where 1=1;
delete from ns_vpnglobal_ns_vpnsessionpolicy_binding where 1=1;
delete from ns_vpnglobal_ns_vpntrafficpolicy_binding where 1=1;
delete from ns_vpnglobal_ns_vpnurl_binding where 1=1;
delete from ns_vpnglobal_ns_vpnportaltheme_binding where 1=1;
delete from ns_vpnglobal_ns_vpneula_binding where 1=1;

delete from ns_vpnsamlssoprofile where 1=1;
delete from ns_vpnsessionaction where 1=1;
delete from ns_vpnsessionpolicy where 1=1;
delete from ns_vpntrafficaction where 1=1;
delete from ns_vpntrafficpolicy where 1=1;
delete from ns_vpnurl where 1=1;
delete from ns_vpnvserver where 1=1;
delete from ns_vpnvserver_ns_appc_server_binding where 1=1;
delete from ns_vpnvserver_ns_appflowpolicy_binding where 1=1;
delete from ns_vpnvserver_ns_cachepolicy_binding where 1=1;
delete from ns_vpnvserver_ns_certplcy_binding where 1=1;
delete from ns_vpnvserver_ns_intranetip_binding where 1=1;
delete from ns_vpnvserver_ns_ldapplcy_binding where 1=1;
delete from ns_vpnvserver_ns_localplcy_binding where 1=1;
delete from ns_vpnvserver_ns_nslogplcy_binding where 1=1;
delete from ns_vpnvserver_ns_preauth_policy_binding where 1=1;
delete from ns_vpnvserver_ns_radiusplcy_binding where 1=1;
delete from ns_vpnvserver_ns_responderpolicy_binding where 1=1;
delete from ns_vpnvserver_ns_rewritepolicy_binding where 1=1;
delete from ns_vpnvserver_ns_samlplcy_binding where 1=1;
delete from ns_vpnvserver_ns_sharefile_server_binding where 1=1;
delete from ns_vpnvserver_ns_staserver_binding where 1=1;
delete from ns_vpnvserver_ns_syslogplcy_binding where 1=1;
delete from ns_vpnvserver_ns_tacacsplcy_binding where 1=1;
delete from ns_vpnvserver_ns_vpnapplication_binding where 1=1;
delete from ns_vpnvserver_ns_vpnclientlessrwpolicy_binding where 1=1;
delete from ns_vpnvserver_ns_vpndoublehopserver_binding where 1=1;
delete from ns_vpnvserver_ns_vpnsessionpolicy_binding where 1=1;
delete from ns_vpnvserver_ns_vpntrafficpolicy_binding where 1=1;
delete from ns_vpnvserver_ns_vpnurl_binding where 1=1;
delete from ns_vpnvserver_ns_icapolicy_binding where 1=1;
UPDATE ns_vpnparam set nsa_httpport=null, nsa_winsip=null, nsa_dnsvsvrname=null, nsa_splitdns="BOTH", nsa_sessto=30, nsa_clisec=null, nsa_clisecgroup=null, nsa_clisecmsg=null, nsa_cliseclog="ON", nsa_splittunnel="OFF", nsa_splittunnel_local_lan="OFF", nsa_splittunnel_rfc1918="OFF", nsa_spoofiip="ON", nsa_killconn="OFF", nsa_traintercep="ON", nsa_winclttype="AGENT", nsa_authaction="DENY", nsa_authgrp=null, nsa_cliidleto=null, nsa_pxyswitch=null, nsa_allpxy=null, nsa_httppxy=null, nsa_ftppxy=null, nsa_sockpxy=null, nsa_gopherpxy=null, nsa_sslpxy=null, nsa_pxyexcpt=null, nsa_pxylocalbypass="DISABLED", nsa_clicleanprompt="ON", nsa_forceclean="NONE", nsa_clinetconf="ON", nsa_clntoptions="ALL", nsa_clntconf="ALL", nsa_ssoenabled="OFF", nsa_sso_credential_index="PRIMARY", nsa_winautologon="OFF", nsa_usemip="NS", nsa_useiip="NOSPILLOVER", nsa_clntdebug="OFF", nsa_loginscript=null, nsa_logoutscript=null, nsa_homepage=null, nsa_wimode="OFF",  nsa_wihomepage=null, nsa_apprecvrhomepage=null, nsa_portal_wimode=null, nsa_client_choices="OFF", nsa_epaclttype=null, nsa_iipsuffix=null, nsa_forcedtimeout=null, nsa_forcedtimeoutwarn=null, nsa_ntdomain=null, nsa_cvpnmode="OFF",  nsa_cvpn_urlencoding="OPAQUE", nsa_cvpn_perscookie="DENY", nsa_email_home=null, nsa_allowedlogin_grps=null, nsa_enccsec="DISABLED", nsa_agsvc_app_token_timeout=100, nsa_agsvc_itms_token_timeout=10, nsa_uitheme=null, nsa_secure_browse="ENABLED", nsa_storefronturl=null, nsa_kcdaccount=null where 1=1;
delete from ns_wi_site where 1=1;
delete from ns_wi_site_ns_access_method_binding where 1=1;
delete from ns_wi_site_ns_intip_binding where 1=1;
delete from ns_wi_site_ns_farmname_binding where 1=1;
delete from ns_wi_pkg where 1=1;
delete from ns_wf_site where 1=1;
delete from ns_wf_pkg where 1=1;
delete from ns_lbprofile where 1=1;
