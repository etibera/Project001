<?php
require_once '../init.php';
class Information {
    function __construct(){
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    function __destruct(){
        $this->conn = null;
    }
    public function get_information($information_id){
        $data = array();
        $stmt = $this->conn->prepare("SELECT id.information_id, id.title, id.description FROM oc_information i LEFT JOIN oc_information_description id ON (i.information_id = id.information_id)
        WHERE  i.status = 1 AND id.information_id = :iid ORDER BY i.sort_order, LCASE(id.title) ASC");
        $stmt->bindValue(':iid', (int) $information_id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if($information_id == 6){
            $data = $this->getPrivacyPolicy();
        }elseif($information_id == 7){
            $data = $this->getTermOfService();
        }else{
            $data = array(
                'title' => html_entity_decode(utf8_decode($data['title'])),
                'description' => html_entity_decode(utf8_decode($data['description']))
            );
        }

        return $data;
    }
    public function getTermOfService(){
        return array(
            'title' => 'Terms Of Service',
            'description' => html_entity_decode(utf8_decode("

            <div class=WordSection1>
            
            <p class=MsoNormal align=center style='text-align:center'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></b></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><b><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'>1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INTRODUCTION</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>1.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Welcome to the Pinoy Electronic Store Online (PESO) platform (the
            &quot;Site&quot;). Please read the following Terms of Service carefully before
            using this Site or creating a new Pinoy Electronic Store Online (PESO) account
            (&quot;Account&quot;) so that you are aware of your legal rights and
            obligations with respect to Pinoy Electronic Store Online (PESO) and its
            affiliates and subsidiaries (individually and collectively, &quot;Pinoy
            Electronic Store Online (PESO)&quot;, &quot;we&quot;, &quot;us&quot; or
            &quot;our&quot;). The &quot;Services&quot; we provide or make available include
            (a) the Site, (b) the services provided by the Site and by Pinoy Electronic
            Store Online (PESO) client software made available through the Site, and (c)
            all information, linked pages, features, data, text, images, photographs,
            graphics, music, sounds, video, messages, tags, content, programming, software,
            application services (including, without limitation, any mobile application
            services) or other materials made available through the Site or its related
            services (&quot;Content&quot;). Any new features added to or augmenting the
            Services are also subject to these Terms of Service. These Terms of Service
            govern your use of Services provided by Pinoy Electronic Store Online (PESO).<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>1.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            The Services include an online platform service that provides a place and
            opportunity for the sale of goods between the buyer (“Buyer”) and the seller
            (“Seller”) (collectively “you”, “Users” or “Parties”). The actual contract for
            sale is between Buyer and Seller and Pinoy Electronic Store Online (PESO) is
            not a party to that or any other contract between Buyer and Seller and accepts
            no obligations in connection with any such contract. Parties to such
            transaction will be entirely responsible for the sales contract between them,
            the listing of goods, warranty of purchase and the like. Pinoy Electronic Store
            Online (PESO) is not involved in the transaction between Users. Pinoy
            Electronic Store Online (PESO) may or may not pre-screen Users or the Content
            or information provided by Users. Pinoy Electronic Store Online (PESO) reserves
            the right to remove any Content or information posted by you on the Site in
            accordance to Section 6.4 herein. Pinoy Electronic Store Online (PESO) cannot
            ensure that Users will actually complete a transaction.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>1.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Before becoming a User of the Site, you must read and accept all of the terms
            and conditions in, and linked to, these Terms of Service and you must consent
            to the processing of your personal data as described in the Privacy Policy
            linked hereto.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>1.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pinoy
            Electronic Store Online (PESO) reserves the right to change, modify, suspend or
            discontinue all or any part of this Site or the Services at any time or upon
            notice as required by local laws. Pinoy Electronic Store Online (PESO) may
            release certain Services or their features in a beta version, which may not
            work correctly or in the same way the final version may work, and we shall not
            be held liable in such instances. Pinoy Electronic Store Online (PESO) may also
            impose limits on certain features or restrict your access to parts of, or the
            entire, Site or Services in its sole discretion and without notice or
            liability.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>1.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pinoy
            Electronic Store Online (PESO) reserves the right to refuse to provide you
            access to the Site or Services or to allow you to open an Account for any
            reason.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>BY USING PINOY ELECTRONIC STORE ONLINE (PESO)
            SERVICES OR OPENING AN ACCOUNT, YOU GIVE YOUR IRREVOCABLE ACCEPTANCE OF AND
            CONSENT TO THE TERMS OF THIS AGREEMENT, INCLUDING THOSE ADDITIONAL TERMS AND
            CONDITIONS AND POLICIES REFERENCED HEREIN AND/OR LINKED HERETO.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>IF YOU DO NOT AGREE TO THESE TERMS, PLEASE DO
            NOT USE OUR SERVICES OR ACCESS THE SITE. IF YOU ARE UNDER THE AGE OF 18 OR THE
            LEGAL AGE FOR GIVING CONSENT HEREUNDER PURSUANT TO THE APPLICABLE LAWS IN YOUR
            COUNTRY (THE “LEGAL AGE”), YOU MUST GET PERMISSION FROM A PARENT OR LEGAL
            GUARDIAN TO OPEN AN ACCOUNT AND THAT PARENT OR LEGAL GUARDIAN MUST AGREE TO THE
            TERMS OF THIS AGREEMENT. IF YOU DO NOT KNOW WHETHER YOU HAVE REACHED THE LEGAL
            AGE, OR DO NOT UNDERSTAND THIS SECTION, PLEASE DO NOT CREATE AN ACCOUNT UNTIL
            YOU HAVE ASKED YOUR PARENT OR LEGAL GUARDIAN FOR HELP. IF YOU ARE THE PARENT OR
            LEGAL GUARDIAN OF A MINOR WHO IS CREATING AN ACCOUNT, YOU MUST ACCEPT THE TERMS
            OF THIS AGREEMENT ON THE MINOR'S BEHALF AND YOU WILL BE RESPONSIBLE FOR ALL USE
            OF THE ACCOUNT OR COMPANY SERVICES USING SUCH ACCOUNT, WHETHER SUCH ACCOUNT IS
            CURRENTLY OPEN OR CREATED LATER.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PRIVACY</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>2.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Your privacy is very important to us at Pinoy Electronic Store Online (PESO).
            To better protect your rights we have provided the Pinoy Electronic Store
            Online (PESO) Privacy Policy to explain our privacy practices in detail. Please
            review the Privacy Policy to understand how Pinoy Electronic Store Online (PESO)
            collects and uses the information associated with your Account and/or your use
            of the Services (the “User Information”). By using the Services or providing
            information on the Site, you:<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(<span class=SpellE>i</span>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            consent to Pinoy Electronic Store Online (PESO)'s collection, use, disclosure
            and/or processing of your Content, personal data and User Information as
            described in the Privacy Policy;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            agree and acknowledge that the proprietary rights of your User Information are
            jointly owned by you and Pinoy Electronic Store Online (PESO); and<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            shall not, whether directly or indirectly, disclose your User Information to
            any third party, or otherwise allow any third party to access or use your User
            Information, without Pinoy Electronic Store Online (PESO)’s prior written
            consent.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>2.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Users in possession of another User’s personal data through the use of the
            Services (the “Receiving Party”) hereby agree that, they will (<span
            class=SpellE>i</span>) comply with all applicable personal data protection laws
            with respect to any such data; (ii) allow the User whose personal data the
            Receiving Party has collected (the “Disclosing Party”) to remove his or her
            data so collected from the Receiving Party’s database; and (iii) allow the
            Disclosing Party to review what information have been collected about them by
            the Receiving Party, in each case of (ii) and (iii) above, in compliance with
            and where required by applicable laws.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LIMITED
            LICENSE</span></b><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>3.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pinoy
            Electronic Store Online (PESO) grants you a limited and revocable license to
            access and use the Services subject to the terms and conditions of these Terms
            of Service. All proprietary Content, trademarks, service marks, brand names,
            logos and other intellectual property (“Intellectual Property”) displayed in
            the Site are the property of Pinoy Electronic Store Online (PESO) and where
            applicable, third party proprietors identified in the Site. No right or <span
            class=SpellE>licence</span> is granted directly or indirectly to any party
            accessing the Site to use or reproduce any Intellectual Property, and no party
            accessing the Site shall claim any right, title or interest therein. By using
            or accessing the Services you agree to comply with the copyright, trademark,
            service mark, and all other applicable laws that protect the Services, the Site
            and its Content. You agree not to copy, distribute, republish, transmit,
            publicly display, publicly perform, modify, adapt, rent, sell, or create
            derivative works of any portion of the Services, the Site or its Content. You
            also may not, without our prior written consent, mirror or frame any part or
            whole of the contents of this Site on any other server or as part of any other
            website. In addition, you agree that you will not use any robot, spider or any
            other automatic device or manual process to monitor or copy our Content,
            without our prior written consent (such consent is deemed given for standard
            search engine technology employed by Internet search websites to direct
            Internet users to this website).<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>3.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            You are welcome to link to the Site from your website, provided that your website
            does not imply any endorsement by or association with Pinoy Electronic Store
            Online (PESO). You acknowledge that Pinoy Electronic Store Online (PESO) may,
            in its sole discretion and at any time, discontinue providing the Services,
            either in part or as a whole, without notice.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SOFTWARE</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>Any
            software provided by us to you as part of the Services is subject to the
            provisions of these Terms of Service. Pinoy Electronic Store Online (PESO)
            reserves all rights to the software not expressly granted by Pinoy Electronic
            Store Online (PESO) hereunder. Any third-party scripts or code, linked to or
            referenced from the Services, are licensed to you by the third parties that own
            such scripts or code, not by Pinoy Electronic Store Online (PESO).<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACCOUNTS
            AND SECURITY</span></b><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>5.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Some functions of our Services require registration for an Account by selecting
            a unique user identification (&quot;User ID&quot;) and password, and by
            providing certain personal information. If you select a User ID that Pinoy Electronic
            Store Online (PESO), in its sole discretion, finds offensive or inappropriate, Pinoy
            Electronic Store Online (PESO) has the right to suspend or terminate your
            Account. You may be able to use your Account to gain access to other products,
            websites or services to which we have enabled access or with which we have tied
            up or collaborated. Pinoy Electronic Store Online (PESO) has not reviewed, and
            assumes no responsibility for any third party content, functionality, security,
            services, privacy policies, or other practices of those products, websites or
            services. If you do so, the terms of service for those products, websites or
            services, including their respective privacy policies, if different from these
            Terms of Service and/or our Privacy Policy, may also apply to your use of those
            products, websites or services.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>5.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            You agree to (a) keep your password confidential and use only your User ID and
            password when logging in, (b) ensure that you log out from your account at the
            end of each session on the Site, (c) immediately notify Pinoy Electronic Store
            Online (PESO) of any <span class=SpellE>unauthorised</span> use of your
            Account, User ID and/or password, and (d) ensure that your Account information
            is accurate and up-to-date. You are fully responsible for all activities that
            occur under your User ID and Account even if such activities or uses were not
            committed by you. Pinoy Electronic Store Online (PESO) will not be liable for
            any loss or damage arising from <span class=SpellE>unauthorised</span> use of
            your password or your failure to comply with this Section.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>5.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            You agree that Pinoy Electronic Store Online (PESO) may for any reason, in its
            sole discretion and with or without notice or liability to you or any third
            party, immediately terminate your Account and your User ID, remove or discard
            from the Site any Content associated with your Account and User ID, withdraw
            any subsidies offered to you, cancel any transactions associated with your
            Account and User ID, temporarily withhold any sale proceeds or refunds, and/or
            take any other actions that Pinoy Electronic Store Online (PESO) deems
            necessary. Grounds for such actions may include, but are not limited to, (a)
            extended periods of inactivity, (b) violation of the letter or spirit of these
            Terms of Service, (c) illegal, fraudulent, harassing, defamatory, threatening
            or abusive behavior (d) having multiple user accounts for illegitimate reasons,
            or (e) behavior that is harmful to other Users, third parties, or the business
            interests of Pinoy Electronic Store Online (PESO). Use of an Account for
            illegal, fraudulent, harassing, defamatory, threatening or abusive purposes may
            be referred to law enforcement authorities without notice to you. If a legal
            dispute arises or law enforcement action is commenced relating to your Account
            or your use of the Services for any reason, Pinoy Electronic Store Online (PESO)
            may terminate your Account immediately with or without notice.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>5.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Users may terminate their Account if they notify Pinoy Electronic Store Online
            (PESO) in writing (including via email at&nbsp;</span><span style='font-size:
            10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#428BCA'>support@pinoyelectronicstore.com</span><span style='font-size:
            10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'> of their desire to do so. Notwithstanding any such termination,
            Users remain responsible and liable for any incomplete transaction (whether
            commenced prior to or after such termination), shipment of the product, payment
            for the product, or the like, and Users must contact Pinoy Electronic Store
            Online (PESO) after he or she has promptly and effectively carried out and
            completed all incomplete transactions according to the Terms of Service. Pinoy
            Electronic Store Online (PESO) shall have no liability, and shall not be liable
            for any damages incurred due to the actions taken in accordance with this
            Section. Users waive any and all claims based on any such action taken by Pinoy
            Electronic Store Online (PESO).<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>5.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            You may only use the Services and/or open an Account if you are located in one
            of our approved countries, as updated from time to time.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>6.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TERM
            OF USE</span></b><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>6.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            The license for use of this Site and the Services is effective until
            terminated. This license will terminate as set forth under these Terms of
            Service or if you fail to comply with any term or condition of these Terms of
            Service. In any such event, Pinoy Electronic Store Online (PESO) may effect
            such termination with or without notice to you.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>6.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            You agree not to:<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            upload, post, transmit or otherwise make available any Content that is
            unlawful, harmful, threatening, abusive, harassing, alarming, distressing,
            tortuous, defamatory, vulgar, obscene, libelous, invasive of another's privacy,
            hateful, or racially, ethnically or otherwise objectionable;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            violate any laws, including without limitation any laws and regulation in
            relation to export and import restrictions, third party rights or our&nbsp;</span><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#428BCA'>Prohibited and Restricted Items policy</span><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            use the Services to harm minors in any way;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            use the Services to impersonate any person or entity, or otherwise misrepresent
            your affiliation with a person or entity;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            forge headers or otherwise manipulate identifiers in order to disguise the
            origin of any Content transmitted through the Services;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(f)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            remove any proprietary notices from the Site;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(g)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            cause, permit or authorize the modification, creation of derivative works, or
            translation of the Services without the express permission of Pinoy Electronic
            Store Online (PESO);<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(h)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            use the Services for the benefit of any third party or any manner not permitted
            by the licenses granted herein;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(<span class=SpellE>i</span>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            use the Services for fraudulent purposes;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(j)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            manipulate the price of any item or interfere with other User's listings;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(k)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            take any action that may undermine the feedback or ratings systems;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(l)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            attempt to decompile, reverse engineer, disassemble or hack the Services (or
            any portion thereof), or to defeat or overcome any encryption technology or
            security measures implemented by Pinoy Electronic Store Online (PESO) with
            respect to the Services and/or data transmitted, processed or stored by Pinoy
            Electronic Store Online (PESO);<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(m)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            harvest or collect any information about or regarding other Account holders,
            including, without limitation, any personal data or information;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(n)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            upload, email, post, transmit or otherwise make available any Content that you
            do not have a right to make available under any law or under contractual or
            fiduciary relationships (such as inside information, proprietary and
            confidential information learned or disclosed as part of employment
            relationships or under nondisclosure agreements);<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(o)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            upload, email, post, transmit or otherwise make available any Content that
            infringes any patent, trademark, trade secret, copyright or other proprietary
            rights of any party;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(p)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            upload, email, post, transmit or otherwise make available any unsolicited or unauthorized
            advertising, promotional materials, &quot;junk mail&quot;, &quot;spam&quot;,
            &quot;chain letters&quot;, &quot;pyramid schemes&quot;, or any other unauthorized
            form of solicitation;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(q)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            upload, email, post, transmit or otherwise make available any material that
            contains software viruses, worms, Trojan-horses or any other computer code,
            routines, files or programs designed to directly or indirectly interfere with,
            manipulate, interrupt, destroy or limit the functionality or integrity of any
            computer software or hardware or data or telecommunications equipment;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(r)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            disrupt the normal flow of dialogue, cause a screen to &quot;scroll&quot;
            faster than other Users of the Services are able to type, or otherwise act in a
            manner that negatively affects other Users' ability to engage in real time
            exchanges;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(s)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            interfere with, manipulate or disrupt the Services or servers or networks
            connected to the Services or any other User's use and enjoyment of the
            Services, or disobey any requirements, procedures, policies or regulations of
            networks connected to the Site;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(t)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            take any action or engage in any conduct that could directly or indirectly
            damage, disable, overburden, or impair the Services or the servers or networks
            connected to the Services;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(u)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            use the Services to intentionally or unintentionally violate any applicable
            local, state, national or international law, rule, code, directive, guideline,
            policy or regulation including, without limitation, laws and requirements
            (whether or not having the force of law) relating to anti-money laundering or
            counter-terrorism;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(v)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            use the Services in violation of or to circumvent any sanctions or embargo
            administered or enforced by the U.S. Department of Treasury’s Office of Foreign
            Assets Control, the United Nations Security Council, the European Union or Her
            Majesty’s Treasury;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(w)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            use the Services to violate the privacy of others or to &quot;stalk&quot; or
            otherwise harass another;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(x)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            infringe the rights of Pinoy Electronic Store Online (PESO), including any
            intellectual property rights and any passing off of the same thereof;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(y)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            use the Services to collect or store personal data about other Users in
            connection with the prohibited conduct and activities set forth above; and/or<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(z)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            list items which infringe upon the copyright, trademark or other intellectual
            property rights of third parties or use the Services in a manner which will
            infringe the intellectual property rights of others.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>6.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            You understand that all Content, whether publicly posted or privately
            transmitted, is the sole responsibility of the person from whom such Content
            originated. This means that you, and not Pinoy Electronic Store Online (PESO),
            are entirely responsible for all Content that you upload, post, email, transmit
            or otherwise make available through the Site. You understand that by using the
            Site, you may be exposed to Content that you may consider to be offensive,
            indecent or objectionable. To the maximum extent permitted by applicable law,
            under no circumstances will Pinoy Electronic Store Online (PESO) be liable in
            any way for any Content, including, but not limited to, any errors or omissions
            in any Content, or any loss or damage of any kind incurred as a result of the
            use of, or reliance on, any Content posted, emailed, transmitted or otherwise
            made available on the Site.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>6.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            You acknowledge that Pinoy Electronic Store Online (PESO) and its designees
            shall have the right (but not the obligation) in their sole discretion to
            pre-screen, refuse, delete, remove or move any Content, including without
            limitation any Content or information posted by you, that is available on the
            Site. Without limiting the foregoing, Pinoy Electronic Store Online (PESO) and
            its designees shall have the right to remove any Content (<span class=SpellE>i</span>)
            that violates these Terms of Service; (ii) if we receive a complaint from
            another User; (iii) if we receive a notice of intellectual property
            infringement or other legal instruction for removal; or (iv) if such Content is
            otherwise objectionable. We may also block delivery of a communication
            (including, without limitation, status updates, postings, messages and/or
            chats) to or from the Services as part of our effort to protect the Services or
            our Users, or otherwise enforce the provisions of these Terms and Conditions.
            You agree that you must evaluate, and bear all risks associated with, the use
            of any Content, including, without limitation, any reliance on the accuracy,
            completeness, or usefulness of such Content. In this regard, you acknowledge
            that you have not and, to the maximum extent permitted by applicable law, may
            not rely on any Content created by Pinoy Electronic Store Online (PESO) or
            submitted to Pinoy Electronic Store Online (PESO), including, without
            limitation, information in Pinoy Electronic Store Online (PESO) Forums and in
            all other parts of the Site.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>6.5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            You acknowledge, consent to and agree that Pinoy Electronic Store Online (PESO)
            may access, preserve and disclose your Account information and Content if
            required to do so by law or pursuant to an order of a court or by any
            governmental or regulatory authority having jurisdiction over Pinoy Electronic
            Store Online (PESO) or in a good faith belief that such access preservation or
            disclosure is reasonably necessary to: (a) comply with legal process; (b)
            enforce these Terms of Service; (c) respond to claims that any Content violates
            the rights of third parties; (d) respond to your requests for customer service;
            or (e) protect the rights, property or personal safety of Pinoy Electronic
            Store Online (PESO), its Users and/or the public.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>7.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VIOLATION
            OF OUR TERMS OF SERVICE</span></b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>7.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Violations of this policy may result in a range of actions, including, without
            limitation, any or all of the following:<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:49.6pt;text-align:justify'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Listing deletion<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Limits placed on Account privileges<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Account suspension and subsequent termination<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Criminal charges<a name=undefined><o:p></o:p></a></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Civil actions, including without limitation a claim for damages and/or interim
            or injunctive relief<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>7.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            If you believe a User on our Site is violating these Terms of Service, please
            contact&nbsp;</span></span><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#428BCA'>support@pinoyelectronicstore.com</span></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>8.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REPORTING
            INTELLECTUAL PROPERTY RIGHTS INFRINGEMENT</span></b></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>8.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            The Users are independent individuals or businesses and they are not associated
            with Pinoy Electronic Store Online (PESO) in any way. Pinoy Electronic Store
            Online (PESO) is neither the agent nor representative of the Users and does not
            hold and/or own any of the merchandises listed on the Site.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>8.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            If you are an intellectual property right owner (“IPR Owner”) or an agent duly authorized
            by an IPR Owner (“IPR Agent”) and you believe that your right or your
            principal’s right has been infringed, please notify us in writing by email
            to&nbsp;</span></span><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#428BCA'>support@pinoyelectronicstore.com</span></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>&nbsp;and copy&nbsp;</span></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#428BCA'>legal@pinoyelectronicstore.com</span></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'> and provide us the
            documents requested below to support your claim. Do allow us time to process
            the information provided. Pinoy Electronic Store Online (PESO) will respond to
            your complaint as soon as practicable.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>8.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Complaints under this Section 8 must be provided in the form prescribed by Pinoy
            Electronic Store Online (PESO), which may be updated from time to time, and
            must include at least the following: (a) a physical or electronic signature of
            an IPR Owner or IPR Agent (collectively, “Informant”); (b) a description of the
            type and nature of intellectual property right that is allegedly infringed and
            proof of rights; (c) details of the listing which contains the alleged
            infringement; (d) sufficient information to allow Pinoy Electronic Store Online
            (PESO) to contact the Informant, such as Informant’s physical address,
            telephone number and e-mail address; (e) a statement by Informant that the
            complaint is filed on good faith belief and that the use of the intellectual
            property as identified by the Informant is not authorized by the IPR Owner or
            the law; (f) a statement by the Informant that the information in the
            notification is accurate, indemnify us for any damages we may suffer as a
            result of the information provided by and that the Informant has the
            appropriate right or is authorized to act on IPR Owner’s behalf to the
            complaint.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>9.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PURCHASE
            AND PAYMENT</span></b></span><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>9.1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) supports one or more of the following
            payment methods in each country it operates in:<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(<span class=SpellE>i</span>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Credit
            Card</u><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>Card payments are processed through
            third-party payment channels and the type of credit cards accepted by these
            payment channels may vary depending on the jurisdiction you are in.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Cash
            on Delivery (COD)</u><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>Pinoy Electronic Store Online (PESO) provides
            COD services in selected countries. Buyers may pay cash directly to the deliver
            agent upon their receipt of the purchased item.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Bank
            Transfer</u><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>Buyer may make payments through an Automated
            Teller Machine or internet bank transfer (“Bank Transfer”) to our designated Pinoy
            Electronic Store Online (PESO) Guarantee Account (as defined in Section 11).
            Buyer must provide Pinoy Electronic Store Online (PESO) with the transfer
            receipt or payment transaction reference for verification purposes through the
            ‘Upload Receipt’ function found in Pinoy Electronic Store Online (PESO)’s app
            as payment confirmation. If payment confirmation is not received by Pinoy
            Electronic Store Online (PESO) within three (3) days, Buyer’s order will be
            cancelled.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>9.2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Buyer may only change their preferred mode of payment for their purchase prior
            to making payment.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>9.3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) takes no responsibility and assume no
            liability for any loss or damages to Buyer arising from shipping information
            and/or payment information entered by Buyer or wrong remittance by Buyer in
            connection with the payment for the items purchased. We reserve the right to
            check whether Buyer is duly authorized to use certain payment method, and may
            suspend the transaction until such authorization is confirmed or cancel the
            relevant transaction where such confirmation is not available.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>9.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            At the moment, Pinoy Electronic Store Online (PESO) is only able to make
            payment to Users via bank transfer. Hence, Users are required to provide Pinoy
            Electronic Store Online (PESO) with his/her banking details in order to receive
            payments i.e. from the sale of item or refund from Pinoy Electronic Store
            Online (PESO).<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>10.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PINOY
            ELECTRONIC STORE ONLINE (PESO) WALLET</span></b></span><span style='mso-bookmark:
            undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>10.1&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) Wallet is a service provided by Pinoy
            Electronic Store Online (PESO) or its authorized agent to facilitate the
            storage of money you receive from your sales proceeds and refunds for purchases
            made via bank transfer, cash payment or your Pinoy Electronic Store Online (PESO)
            Wallet. The sum of this money, minus any withdrawals, will be reflected as your
            Pinoy Electronic Store Online (PESO) Wallet balance.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>10.2&nbsp;&nbsp;&nbsp;&nbsp;
            You may transfer funds from your Pinoy Electronic Store Online (PESO) Wallet
            (up to the amount of your Pinoy Electronic Store Online (PESO) Wallet balance)
            to your linked bank account (“Linked Account”) by submitting a transfer request
            (“Withdrawal Request”) a maximum of once per day. Pinoy Electronic Store Online
            (PESO) may also automatically transfer funds from your Pinoy Electronic Store
            Online (PESO) Wallet to your Linked Account on a regular basis, as determined
            by Pinoy Electronic Store Online (PESO). Pinoy Electronic Store Online (PESO)
            shall only process such transfers on business days and such transfers may take
            up to two business days to be credited to your Linked Account.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>10.3&nbsp;&nbsp;&nbsp;&nbsp;
            Each User is entitled to a maximum number of one (1) free Withdrawal Request
            per week. Pinoy Electronic Store Online (PESO) may impose a fee of &#8369;15
            (&#8369;500 for BPI Family Savings) for each additional Withdrawal Request made
            in excess of such maximum number in a given week (“Withdrawal Fee”). The
            maximum number of free Withdrawal Requests each User is entitled to and the
            amount of Withdrawal Fees charged are subject to change at Pinoy Electronic
            Store Online (PESO)’s discretion.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>10.4&nbsp;&nbsp;&nbsp;&nbsp;
            Money from your sale of items on Pinoy Electronic Store Online (PESO) shall be
            credited to your Pinoy Electronic Store Online (PESO) Wallet within three (3)
            days after the item is delivered to Buyer or immediately after Buyer has
            acknowledged that they have received the item. Money from refunds of items on Pinoy
            Electronic Store Online (PESO) shall be credited to your Pinoy Electronic Store
            Online (PESO) Wallet within one (1) day of the return or refund request being
            approved.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>10.5&nbsp;&nbsp;&nbsp;&nbsp;
            Once submitted, you may not modify or cancel a Withdrawal Request.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>10.6&nbsp;&nbsp;&nbsp;&nbsp;
            If there is an error in the processing of any transaction, you authorize us to
            initiate debit or credit entries to your designated bank account, to correct
            such error, provided that any such correction is made in accordance with
            applicable laws and regulations. If we are unable to debit your designated bank
            account for any reason, you authorize us to resubmit the debit, plus any
            applicable fees, to any other bank account or payment instrument that you have
            on file with us or to deduct the debit and applicable fees from your Pinoy
            Electronic Store Online (PESO) Wallet balance in the future.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>10.7&nbsp;&nbsp;&nbsp;&nbsp;
            You authorize us to initiate debit or credit entries to your Pinoy Electronic
            Store Online (PESO) Wallet:<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(<span class=SpellE>i</span>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            to correct any errors in the processing of any transaction;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:70.9pt;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            where Pinoy Electronic Store Online (PESO) has determined that you have engaged
            in fraudulent or suspicious activity and/or transactions;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            in connection with any lost, damaged or incorrect items;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-indent:-27.8pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            in connection with any rewards or rebates;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-indent:-27.8pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(v)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            in connection with any uncharged fees;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-indent:-27.8pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(vi)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            in connection with the settlement of any transaction dispute, including any
            compensation due to, or from, you;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-indent:-27.8pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(vii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; in
            connection with any banned items or items that are detained by customs; and<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-indent:-27.8pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(viii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; in connection
            with any change of mind agreed to by both Buyer and Seller.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>11.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PINOY
            ELECTRONIC STORE ONLINE (PESO) GUARANTEE</span></b></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>11.1&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) Guarantee is a service provided by Pinoy
            Electronic Store Online (PESO) or its authorized agent to protect purchases. To
            protect against the risk of liability, payment for purchases made to Seller
            using the Services will be held by Pinoy Electronic Store Online (PESO) or its authorized
            agent (“Pinoy Electronic Store Online (PESO) Guarantee Account”). Seller will
            not receive interest or other earnings from the sum you have paid into Pinoy
            Electronic Store Online (PESO) Guarantee Account.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>11.2&nbsp;&nbsp;&nbsp;&nbsp;
            After Buyer makes payment for his/her order (“Buyer’s Purchase Monies”),
            Buyer’s Purchase Monies will be held in Pinoy Electronic Store Online (PESO)
            Guarantee Account until:<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(a)&nbsp; &nbsp;
            &nbsp; Buyer sends confirmation to Pinoy Electronic Store Online (PESO) that
            Buyer has received his/her goods, in which case, unless 11.2(d) applies, Pinoy
            Electronic Store Online (PESO) will release Buyer’s Purchase Monies (less the
            Transaction Fee (defined below), and&nbsp;(if applicable) the Cross Border Fee
            (defined below)) in Pinoy Electronic Store Online (PESO) Guarantee Account to
            Seller;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(b)&nbsp; &nbsp;
            &nbsp;Pinoy Electronic Store Online (PESO) Guarantee Period (or any approved
            extension under 11.3) expires, in which case, unless 11.2(c) or 11.2(d)
            applies, Pinoy Electronic Store Online (PESO) will release Buyer’s Purchase
            Monies (less the Transaction Fee (defined below), and&nbsp;(if applicable) the
            Cross Border Fee (defined below)) in Pinoy Electronic Store Online (PESO)
            Guarantee Account to Seller;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(c)&nbsp; &nbsp;
            &nbsp; Pinoy Electronic Store Online (PESO) determines that Buyer’s application
            for a return of goods and/or refund is successful, in which case, unless
            11.2(d) applies, Pinoy Electronic Store Online (PESO) will provide a refund to
            Buyer, subject to and in accordance with the Refunds and Return Policy;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(d)&nbsp; &nbsp; such
            other time as Pinoy Electronic Store Online (PESO) reasonably determines that a
            distribution of Buyer’s Purchase Monies (less the Transaction Fee (defined
            below), and&nbsp;(if applicable) the Cross Border Fee (defined below)) is
            appropriate, including, without limitation, where it deems reasonably necessary
            to comply with applicable law or a court order or to enforce these Terms of
            Service.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:28.5pt;text-align:justify;text-indent:-7.65pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:28.5pt;text-align:justify;text-indent:-.15pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>Pinoy Electronic Store
            Online (PESO) Guarantee is only offered to Buyers who have made payment through
            the channels provided by Pinoy Electronic Store Online (PESO) into Pinoy
            Electronic Store Online (PESO) Guarantee Account. Offline arrangements between
            Buyer and Seller will not be covered under Pinoy Electronic Store Online (PESO)
            Guarantee.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>11.3&nbsp;&nbsp;&nbsp;&nbsp;
            Payments made through Pinoy Electronic Store Online (PESO) channels will be
            held in the Pinoy Electronic Store Online (PESO) Guarantee Account for a
            specified period of time (the “Pinoy Electronic Store Online (PESO) Guarantee
            Period”). To find out more about the Pinoy Electronic Store Online (PESO)
            Guarantee Period, please click&nbsp;</span></span><span style='mso-bookmark:
            undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#428BCA'>this</span></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>&nbsp;link. Buyer may
            apply for a one-time extension of Pinoy Electronic Store Online (PESO)
            Guarantee Period prior to the expiry of the applicable Pinoy Electronic Store
            Online (PESO) Guarantee Period, subject to and in accordance with the Refunds
            and Return Policy. Upon Buyer’s application, Pinoy Electronic Store Online (PESO)
            Guarantee Period may be extended for a maximum period of three (3) days unless Pinoy
            Electronic Store Online (PESO) in its sole discretion determines that a longer
            extension is appropriate or required.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>11.4&nbsp;&nbsp;&nbsp;&nbsp;
            If, for any reason, the Seller's bank account cannot be credited and/or the
            Seller cannot be contacted, Pinoy Electronic Store Online (PESO) will use
            reasonable endeavors to contact the Seller using the contact details provided
            by him/her. In the event that the Seller cannot be contacted and the Buyer’s
            Purchase Monies remain unclaimed for more than six (6) months after they become
            due to the Seller, Pinoy Electronic Store Online (PESO) will deal with such
            unclaimed Buyer's Purchase Monies in accordance with any applicable laws.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>11.5&nbsp;&nbsp;&nbsp;&nbsp;
            Seller/Buyer must be the beneficial owner of the Account and conduct transaction
            on the Site only on behalf of him or herself. Pinoy Electronic Store Online (PESO)
            may require Seller or Buyer to provide his or her personal data such as recent
            identity photograph, bank account details and/or any other such documentation
            necessary, for verification purposes, including verification required by third
            party payment processing and logistic service providers. Seller/Buyer hereby
            grants Pinoy Electronic Store Online (PESO) his/her consent to use or provide
            to third party his/her personal data to facilitate his/her use of the Site.
            Further, Seller/Buyer authorizes Pinoy Electronic Store Online (PESO) to use
            his/her personal data to make any inquires we consider necessary to validate
            his/her identity with the appropriate entity such as his/her bank. For more
            information in relation to how Pinoy Electronic Store Online (PESO) handles
            your personal information, please visit our&nbsp;</span></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#428BCA'>Privacy Policy page</span></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>11.6&nbsp;&nbsp;&nbsp;&nbsp;
            The Pinoy Electronic Store Online (PESO) Guarantee is in addition and without
            limitation to Buyer’s and Seller’s obligations under applicable law, which may
            go above and beyond what is provided for by the Pinoy Electronic Store Online (PESO)
            Guarantee. The Pinoy Electronic Store Online (PESO) Guarantee is neither
            intended nor designed to assist Buyer or Seller in complying with its own legal
            obligations, for which each party will remain solely responsible, and Pinoy
            Electronic Store Online (PESO) accepts no liability in connection with the
            same. Without limitation, the Pinoy Electronic Store Online (PESO) Guarantee
            does not constitute a product warranty.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>11.7&nbsp;&nbsp;&nbsp;&nbsp;
            Buyer and Seller acknowledge and agree that Pinoy Electronic Store Online (PESO)’s
            decision (including any appeals) in respect of and relating to any issues
            concerning the Pinoy Electronic Store Online (PESO) Guarantee is final.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>11.8&nbsp;&nbsp;&nbsp;&nbsp;
            For the avoidance of doubt, any transactions not conducted on the Site will not
            qualify for the protection offered by Pinoy Electronic Store Online (PESO)
            Guarantee.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>12.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pinoy
            Electronic Store Online (PESO) Coin Reward System</span></b></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>12.1&nbsp;&nbsp;&nbsp;&nbsp;
            Users may earn loyalty points (“Pinoy Electronic Store Online (PESO) Coin”) by
            buying merchandises on the Site using the Pinoy Electronic Store Online (PESO)
            Guarantee system or through participation in other Pinoy Electronic Store
            Online (PESO) activities as Pinoy Electronic Store Online (PESO) may from time
            to time determines (“Eligible Activities”) based on the conversion rate
            determined by Pinoy Electronic Store Online (PESO) in its sole discretion.
            Generally, Pinoy Electronic Store Online (PESO) Coin will be credited to a
            User’s Account upon the completion of a successful transaction or activity
            approved by Pinoy Electronic Store Online (PESO). You are eligible to
            participate in the Pinoy Electronic Store Online (PESO) Coin reward system if
            you are a User and your Account does not expressly exclude you from
            participation.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>12.2&nbsp;&nbsp;&nbsp;&nbsp;
            Transaction not completed on the Site using Pinoy Electronic Store Online (PESO)
            Guarantee do not qualify for the Pinoy Electronic Store Online (PESO) Coin
            reward system. Pinoy Electronic Store Online (PESO) may at its sole discretion
            exclude categories of items from the Pinoy Electronic Store Online (PESO) Coin
            reward system.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>12.3&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) Coin has no monetary value, does not
            constitute your property and cannot be purchased, sold, transferred or redeemed
            for cash.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>12.4&nbsp;&nbsp;&nbsp;&nbsp;
            Subject to Pinoy Electronic Store Online (PESO) rules and regulation as
            determined, varied or modified by Pinoy Electronic Store Online (PESO) from
            time to time, subject to any cap imposed by Pinoy Electronic Store Online (PESO)
            at its sole discretion, User may redeem Pinoy Electronic Store Online (PESO)
            Coin by sending a request to Pinoy Electronic Store Online (PESO) and use Pinoy
            Electronic Store Online (PESO) Coin to offset the purchase price of selected
            items when making purchases on the Site as advised by Pinoy Electronic Store
            Online (PESO) from time to time. All refunds will be subject to Pinoy
            Electronic Store Online (PESO)’s Refunds and Return Policy under Section 14.4.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>12.5&nbsp;&nbsp;&nbsp;&nbsp;
            The Pinoy Electronic Store Online (PESO) Coin you redeem will be deducted from
            your Pinoy Electronic Store Online (PESO) Coin balance. Each Pinoy Electronic
            Store Online (PESO) Coin comes with an expiry date. Do check your account
            details on the Site for Pinoy Electronic Store Online (PESO) Coin balances and
            expiry date.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>12.6&nbsp;&nbsp;&nbsp;&nbsp;
            From time to time, we may tell you that bonus Pinoy Electronic Store Online (PESO)
            Coin will be awarded for particular Eligible Activities. This may include but
            is not limited to purchases you make at participating Sellers or pursuant to
            specific promotional offers. We will notify you of the terms of such bonus
            awards if any from time to time.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>12.7&nbsp;&nbsp;&nbsp;&nbsp;
            If you have a dispute in relation to the number of Pinoy Electronic Store
            Online (PESO) Coin which you have been awarded in respect of an Eligible
            Activity, such a dispute must be made within one (1) month from the date of the
            Eligible Activity. We may require you to provide evidence to support your
            claim.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>12.8&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) gives no warranty and accepts no
            responsibility as to the ultimate tax treatment of Pinoy Electronic Store
            Online (PESO) Coin. You will need to check with your tax advisor whether
            receiving Pinoy Electronic Store Online (PESO) Coin affects your tax situation.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>12.9&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) reserves the right to (<span class=SpellE>i</span>)
            discontinue the Pinoy Electronic Store Online (PESO) Coin Reward System at any time
            at its sole discretion and (ii) cancel or suspend a User’s right to participate
            in Pinoy Electronic Store Online (PESO) Coin Reward System, including the
            ability to earn and redeem Pinoy Electronic Store Online (PESO) Coin at its
            sole discretion.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>13.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DELIVERY</span></b></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>13.1&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) will inform Seller when Pinoy Electronic
            Store Online (PESO) receives Buyer’s Purchase Monies. Unless otherwise agreed
            with Pinoy Electronic Store Online (PESO), Seller should then make the necessary
            arrangements to have the purchased item delivered to Buyer and provide details
            such as the name of the delivery company, the tracking number, etc. to Buyer
            through the Site.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>13.2&nbsp;&nbsp;&nbsp;&nbsp;
            Seller must use his/her best effort to ensure that Buyer receives the purchased
            items within, whichever applicable, the Pinoy Electronic Store Online (PESO)
            Guarantee Period or the time period specified (for offline payment) by Seller
            on Seller’s listing.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>13.3&nbsp;&nbsp;&nbsp;&nbsp;
            Users understand that Seller bears all risk attached to the delivery of the
            purchased item(s) and warrants that he/she has or will obtain adequate
            insurance coverage for the delivery of the purchased item(s). In the event
            where the purchased item(s) is damaged, lost or failure of delivery during the
            course of delivery, Users acknowledge and agree that Pinoy Electronic Store
            Online (PESO) will not be liable for any damage, expense, cost or fees resulted
            therefrom and Seller and/or Buyer will reach out to the logistic service
            provider to resolve such dispute.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>13.4&nbsp;
            &nbsp;&nbsp;For Cross-Border Transaction. Users understand and acknowledge
            that, where a product listing states that the product will ship from overseas,
            such product is being sold from a Seller based outside of the Philippines, and
            the importation and exportation of such product is subject to local laws and
            regulations. Users should familiarize themselves with all import and export
            restrictions that apply to the designating country. Users acknowledge that Pinoy
            Electronic Store Online (PESO) cannot provide any legal advice in this regard
            and agrees that Pinoy Electronic Store Online (PESO) shall not bear any risks
            or liabilities associated with the import and export of such products to the
            Philippines.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>14.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CANCELLATION,
            RETURN AND REFUND</span></b></span><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>14.1&nbsp;&nbsp;&nbsp;&nbsp;
            Buyer may only cancel his/her order prior to the payment of Buyer’s Purchase
            Monies into Pinoy Electronic Store Online (PESO) Guarantee Account.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>14.2&nbsp;&nbsp;&nbsp;&nbsp;
            Buyer may apply for the return of the purchased item and refund prior to the
            expiry of Pinoy Electronic Store Online (PESO) Guarantee Period, if applicable,
            subject to and in accordance with Pinoy Electronic Store Online (PESO)’s
            Refunds and Return Policy. Please refer to Pinoy Electronic Store Online (PESO)’s&nbsp;</span></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#428BCA'>Refunds and Return
            Policy</span></span><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;for further information.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>14.3&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) reserves the right to cancel any
            transaction on the Site for valid/legal grounds, such as in certain cases where
            Seller and Buyer cannot or do not fulfil their obligations under the contract
            for sale between Seller and Buyer. Buyer agrees that Buyer’s sole remedy will
            be to receive a refund of the Buyer’s Purchase Monies paid into Pinoy
            Electronic Store Online (PESO) Guarantee Account.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>14.4&nbsp;&nbsp;&nbsp;&nbsp;
            If you have redeemed Pinoy Electronic Store Online (PESO) Coin for your
            transaction and you are successful in obtaining a refund based on Pinoy
            Electronic Store Online (PESO)’s Refunds and Return Policy, Pinoy Electronic
            Store Online (PESO) shall refund the monies you have actually paid for the item
            and credit back any redeemed Pinoy Electronic Store Online (PESO) Coin to your
            Account separately.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>14.5&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) does not monitor the cancellation, return
            and refund process for offline payment.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>15.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELLER’S
            RESPONSIBILITIES</span></b></span><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>15.1&nbsp;&nbsp;&nbsp;&nbsp;
            Seller shall properly manage and ensure that relevant information such as the
            price and the details of items, inventory amount and terms and conditions for
            sales is updated on Seller’s listing and shall not post inaccurate or
            misleading information.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>15.2&nbsp;&nbsp;&nbsp;&nbsp;
            The price of items for sale will be determined by the Seller at his/her own
            discretion. The price of an item and shipping charges shall include the entire
            amount to be charged to Buyer such as sales tax, value-added tax, tariffs, etc.
            and Seller shall not charge Buyer such amount additionally and separately.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>15.3&nbsp;&nbsp;&nbsp;&nbsp;
            Seller agrees that Pinoy Electronic Store Online (PESO) may at its discretion
            engage in promotional activities to induce transactions between Buyer and
            Seller by reducing, discounting or refunding fees, or in other ways. The final
            price that Buyer will pay actually will be the price that such adjustment is
            applied to.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>15.4&nbsp;&nbsp;&nbsp;&nbsp;
            For the purpose of promoting the sales of the items listed by Seller, Pinoy
            Electronic Store Online (PESO) may post such items (at adjusted price) on
            third-party websites (such as portal sites and price comparison sites) and
            other websites (domestic or foreign) operated by Pinoy Electronic Store Online
            (PESO).<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>15.5&nbsp;&nbsp;&nbsp;&nbsp;
            Seller shall issue receipts, credit card slips or tax invoices to Buyer on
            request.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>15.6&nbsp;&nbsp;&nbsp;&nbsp;
            Seller acknowledges and agrees that Seller will be responsible for paying all
            taxes, customs and duties for the item sold and Pinoy Electronic Store Online (PESO)
            cannot provide any legal or tax advice in this regard. As tax laws and
            regulations may change from time to time, Sellers are advised to seek
            professional advice if in
            doubt.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>15.7&nbsp;&nbsp;&nbsp;&nbsp;
            Seller acknowledge and agrees that Seller’s violation of any of Pinoy
            Electronic Store Online (PESO)’s polices will result in a range of actions as
            stated in Section 7.1.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>16.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Paid
            Advertising</span></b></span><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>16.1&nbsp; &nbsp; Pinoy
            Electronic Store Online (PESO) will be launching keyword advertising and/or
            other advertising services (hereinafter referred to as &quot;Paid
            Advertising&quot;) on its&nbsp;</span></span><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#428BCA'>Paid Advertising Site</span></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>&nbsp;on an ongoing
            basis. Sellers may purchase the Paid Advertising services. Pinoy Electronic
            Store Online (PESO) provides the Paid Advertising services in accordance with
            these Terms of Service and any explanatory materials published on this Site,
            the Paid Advertising Site or otherwise communicated to Sellers in writing
            (hereinafter referred to as the &quot;Paid Advertising Rules&quot;). Sellers
            who purchase Paid Advertising services agree to be bound by the Paid
            Advertising Rules. If you are not agreeable to being bound by the Paid
            Advertising Rules, do not buy any Paid Advertising Services.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>16.2&nbsp; &nbsp;In
            order to purchase Paid Advertising services, you must be an eligible Seller
            under the Paid Advertising Rules. At the time when you purchase and pay for the
            Paid Advertising Services, your Account must not be suspended.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>16.3&nbsp; &nbsp;You
            may purchase Paid Advertising services by purchasing advertising credits on the
            Paid Advertising Site (“Advertising Credits”), and fees payable for the Paid
            Advertising services will be deducted from the Advertising Credits on a
            pay-per-click basis, as determined by Pinoy Electronic Store Online (PESO). All
            Advertising Credits will be subject to value-added services tax. Except as
            otherwise provided in the applicable Paid Advertising Rules, you may not cancel
            the order and/or request for a refund after you have purchased Advertising
            Credits and completed the payment process.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>16.4&nbsp; &nbsp;You
            have the option to purchase the keyword advertising service at the time you
            list an item for sale or subsequently. When you purchase the keyword
            advertising service, you can set different budgets, keywords, marketing
            periods, etc. for each item in accordance with the Paid Advertising Rules. The
            keyword advertising service for each item will be activated and will expire on
            the respective dates set by you (the “Keyword Advertising Period”). You will
            not be entitled to transfer the remaining Keyword Advertising Period or
            Advertising Credits to other items if an item is sold or unlisted during the
            Keyword Advertising Period you set for that item. The Advertising Credits will
            also not be refunded.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>16.5&nbsp; &nbsp;The
            goods you list on the Site must comply with all relevant laws and regulations,
            the Paid Advertising Rules, these Terms of Service and the Prohibited and
            Restricted Items Policy. You understand and agree that Pinoy Electronic Store
            Online (PESO) has the right to immediately remove any listing which violates
            any of the foregoing and any Paid Advertising fees that you have paid or
            Advertising Credits you have used in relation to any listing removed pursuant
            to this Section 16.5 will not be refunded. Pinoy Electronic Store Online (PESO)
            will also not be liable to compensate you for any loss whatsoever in relation
            to listings removed pursuant to this Section 16.5.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>16.6&nbsp; &nbsp; You
            understand and agree that Pinoy Electronic Store Online (PESO) does not warrant
            or guarantee any increase in viewership or sales of your items as a result of
            the Paid Advertising services.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>16.7&nbsp; &nbsp; You
            are advised to only purchase Paid Advertising services after fully considering
            your budget and intended advertising objectives. Except as otherwise provided
            in these Terms of Service or the Paid Advertising Rules, Pinoy Electronic Store
            Online (PESO) shall not be liable for any compensation or be subject to any
            liability (including but not limited to actual expenses and lost profits) for
            the results or intended results of any Paid Advertising service.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>16.8&nbsp; &nbsp; IF,
            NOTWITHSTANDING ANYTHING IN THESE TERMS OF SERVICE, PINOY ELECTRONIC STORE
            ONLINE (PESO) IS FOUND BY A COURT OF COMPETENT JURISDICTION TO BE LIABLE
            (INCLUDING FOR GROSS NEGLIGENCE) IN RELATION TO ANY PAID ADVERTISING SERVICE,
            THEN, TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, ITS LIABILITY TO YOU
            OR TO ANY THIRD PARTY IS LIMITED TO THE AMOUNT PAID BY YOU FOR THE PAID
            ADVERTISING SERVICE IN QUESTION ONLY.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>17.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PURCHASE
            AND SALE OF ALCOHOL AND TOBACCO OR TOBACCO-RELATED PRODUCTS</span></b></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>17.1&nbsp;&nbsp;&nbsp;&nbsp;
            The purchase and sale of alcoholic products (“Alcohol”) and tobacco or
            tobacco-related products, including without limitation electric cigarettes
            (“Tobacco Products”) on the Site is permitted by Pinoy Electronic Store Online
            (PESO) subject to the terms and conditions of this Section 17.&nbsp; If you are
            a buyer of Alcohol (“Alcohol Buyer”) or Tobacco Products (“Tobacco Products
            Buyer”), you will be deemed to have consented to the terms and conditions in
            this Section 17 when you purchase Alcohol or Tobacco Products on the Site.
            Similarly, if you are an approved seller of Alcohol (“Alcohol Seller”) or
            Tobacco Products (“Tobacco Products Seller”), you will be deemed to have
            consented to the terms and conditions in this Section 17 when you sell Alcohol
            or Tobacco Products on the Site.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>17.2&nbsp;&nbsp;&nbsp;&nbsp;
            If you are an Alcohol Buyer or Tobacco Products Buyer:<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(<span class=SpellE>i</span>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            you represent and warrant that you and (if applicable) the person receiving the
            Alcohol or Tobacco Products (“Recipient”) are aged 18 or above; and<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            if requested by an Alcohol Seller, Tobacco Products Seller or Pinoy Electronic
            Store Online (PESO) (or its agents), you and/or the Recipient shall provide
            photo identification for age verification purposes.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>17.3&nbsp;&nbsp;&nbsp;&nbsp;
            If you are an Alcohol Seller or Tobacco Products Seller, you represent and
            warrant that:<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(<span class=SpellE>i</span>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            you hold all necessary licenses and/or permits to sell Alcohol or Tobacco
            Products through the Site; and<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            all information and documents provided to Pinoy Electronic Store Online (PESO)
            are true and accurate.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>17.4&nbsp;&nbsp;&nbsp;&nbsp;
            When delivering Alcohol to an Alcohol Buyer:<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(<span class=SpellE>i</span>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            the delivery agent reserves the right to request for valid photo identification
            for age verification purposes; and<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) (via the delivery agent) reserves the
            right to refuse the delivery of Alcohol if the Alcohol Buyer and/or the
            Recipient appears intoxicated or is unable to provide valid photo
            identification for age verification purposes.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>17.5&nbsp;&nbsp;&nbsp;&nbsp;
            When delivering Tobacco Products to Tobacco Products Buyer:<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(<span class=SpellE>i</span>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            the delivery agent reserves the right to request for valid photo identification
            for age verification purposes; and<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) (via the delivery agent) reserves the
            right to refuse the delivery of Tobacco Products if the Tobacco Products Buyer
            and/or the Recipient is unable to provide valid photo identification for age
            verification purposes.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>17.6&nbsp;&nbsp;&nbsp;&nbsp;
            Each Alcohol Buyer and Alcohol Seller, and each Tobacco Products Buyer and
            Tobacco Products Seller, severally agrees to indemnify, defend and hold
            harmless Pinoy Electronic Store Online (PESO), and its shareholders,
            subsidiaries, affiliates, directors, officers, agents, co-branders or other
            partners, and employees (collectively, the &quot;Indemnified Parties&quot;)
            from and against any and all claims, actions, proceedings, and suits and all
            related liabilities, damages, settlements, penalties, fines, costs and expenses
            (including, without limitation, any other dispute resolution expenses) incurred
            by any Indemnified Party arising out of or relating to: (a) any inaccuracy or
            breach of its representations in Section 17.2 and/or Section 17.3 (as
            applicable); and (b) its breach of any law or any rights of a third party.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>18.&nbsp;
            &nbsp; &nbsp; &nbsp; TRANSACTION&nbsp;FEES</span></b></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>18.1&nbsp;&nbsp;&nbsp;&nbsp;Effective
            May 1, 2019, Pinoy Electronic Store Online (PESO) will charge a fee for all
            successful transactions completed via bank transfer, credit card or Pinoy
            Electronic Store Online (PESO) Wallet on the Site (“Transaction Fee”). The
            Transaction Fee is borne by the Seller, and is calculated as one point five
            percent (1.5%) of the Buyer’s Purchase Monies, rounded to the nearest PESO. The
            Transaction Fee is inclusive of value-added tax. Pinoy Electronic Store Online
            (PESO) shall issue receipts or tax invoices for the Transaction Fee upon
            request.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>18.2&nbsp;&nbsp;&nbsp;&nbsp;For
            Sellers located outside of the Philippines, Pinoy Electronic Store Online (PESO)
            charges a fee for all successful transactions completed via bank transfer,
            credit card or Pinoy Electronic Store Online (PESO) Wallet on the Site (“Cross
            Border Fee”). The Cross Border Fee is borne by the Seller, and is calculated
            according to the rates as notified to such Sellers from time to time on the
            Site.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>18.3&nbsp;&nbsp;&nbsp;Following
            the successful completion of a transaction, Pinoy Electronic Store Online (PESO)
            shall deduct the Transaction Fee and the Cross Border Fee (as applicable) from
            the Buyer’s Purchase Monies, and remit the balance to the Seller in accordance
            with Section 11.2. Pinoy Electronic Store Online (PESO) shall issue receipts or
            tax invoices for the Transaction Fee paid by Seller on request.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal><span style='mso-bookmark:undefined'><span style='font-size:
            10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>19.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DISPUTES</span></b></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>19.1&nbsp;&nbsp;&nbsp;&nbsp;
            In the event a problem arises in a transaction, the Buyer and Seller agree to
            communicate with each other first to attempt to resolve such dispute by mutual
            discussions, which Pinoy Electronic Store Online (PESO) shall use reasonable
            commercial efforts to facilitate. If the matter cannot be resolved by mutual
            discussions, Users may approach the claims tribunal of their local jurisdiction
            to resolve any dispute arising from a transaction.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>19.2&nbsp;&nbsp;&nbsp;&nbsp;
            Each Buyer and Seller covenants and agrees that it will not bring suit or
            otherwise assert any claim against Pinoy Electronic Store Online (PESO) or its
            Affiliates (except where Pinoy Electronic Store Online (PESO) or its Affiliates
            is the Seller of the product that the claim relates to) in relation to any
            transaction made on the Site or any dispute related to such transaction.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>19.3&nbsp;&nbsp;&nbsp;&nbsp;
            Users covered under Pinoy Electronic Store Online (PESO) Guarantee may send
            written request to Pinoy Electronic Store Online (PESO) to assist them in
            resolving issues which may arise from a transaction upon request. Pinoy
            Electronic Store Online (PESO) may, at its sole discretion and with absolutely
            no liability to Seller and Buyer, take all necessary steps to assist Users
            resolving their dispute. For more information, please refer to Pinoy Electronic
            Store Online (PESO)’s&nbsp;</span></span><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#428BCA'>Refunds and Return Policy</span></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>19.4&nbsp;&nbsp;&nbsp;&nbsp;
            To be clear, the services provided under this Section 19 are only available to
            Buyers covered under Pinoy Electronic Store Online (PESO) Guarantee. Buyer
            using other payment means for his/her purchase should contact Seller directly.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>20.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FEEDBACK</span></b></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>20.1&nbsp;&nbsp;&nbsp;&nbsp;
            Pinoy Electronic Store Online (PESO) welcomes information and feedback from our
            Users which will enable Pinoy Electronic Store Online (PESO) to improve the
            quality of service provided. Please refer to our feedback procedure below for
            further information:<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:42.55pt;text-align:justify'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(<span class=SpellE>i</span>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Feedback may be made in writing through email to or using the feedback form
            found on the App.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Anonymous feedback will not be accepted.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(iii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Users affected by the feedback should be fully informed of all facts and given
            the opportunity to put forward their case.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(iv)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Vague and defamatory feedback will not be entertained.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>21.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DISCLAIMERS</span></b></span><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>21.1&nbsp;&nbsp;&nbsp;&nbsp;
            THE SERVICES ARE PROVIDED &quot;AS IS&quot; AND WITHOUT ANY WARRANTIES, CLAIMS
            OR REPRESENTATIONS MADE BY PINOY ELECTRONIC STORE ONLINE (PESO) OF ANY KIND
            EITHER EXPRESSED, IMPLIED OR STATUTORY WITH RESPECT TO THE SERVICES, INCLUDING,
            WITHOUT LIMITATION, WARRANTIES OF QUALITY, PERFORMANCE, NON-INFRINGEMENT,
            MERCHANTABILITY, OR FITNESS FOR A PARTICULAR PURPOSE, NOR ARE THERE ANY
            WARRANTIES CREATED BY COURSE OF DEALING, COURSE OF PERFORMANCE OR TRADE USAGE.
            WITHOUT LIMITING THE FOREGOING AND TO THE MAXIMUM EXTENT PERMITTED BY
            APPLICABLE LAW, PINOY ELECTRONIC STORE ONLINE (PESO) DOES NOT WARRANT THAT THE
            SERVICES, THIS SITE OR THE FUNCTIONS CONTAINED THEREIN WILL BE AVAILABLE,
            ACCESSIBLE, UNINTERRUPTED, TIMELY, SECURE, ACCURATE, COMPLETE OR ERROR-FREE,
            THAT DEFECTS, IF ANY, WILL BE CORRECTED, OR THAT THIS SITE AND/OR THE SERVER
            THAT MAKES THE SAME AVAILABLE ARE FREE OF VIRUSES, CLOCKS, TIMERS, COUNTERS,
            WORMS, SOFTWARE LOCKS, DROP DEAD DEVICES, TROJAN-HORSES, ROUTINGS, TRAP DOORS,
            TIME BOMBS OR ANY OTHER HARMFUL CODES, INSTRUCTIONS, PROGRAMS OR COMPONENTS.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>21.2&nbsp;&nbsp;&nbsp;&nbsp;
            YOU ACKNOWLEDGE THAT THE ENTIRE RISK ARISING OUT OF THE USE OR PERFORMANCE OF
            THE SITE AND/OR THE SERVICES REMAINS WITH YOU TO THE MAXIMUM EXTENT PERMITTED
            BY APPLICABLE LAW.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>21.3&nbsp;&nbsp;&nbsp;&nbsp;
            PINOY ELECTRONIC STORE ONLINE (PESO) HAS NO CONTROL OVER AND, TO THE MAXIMUM
            EXTENT PERMITTED BY APPLICABLE LAW, DOES NOT GUARANTEE OR ACCEPT ANY
            RESPONSIBILITY FOR: (A) THE FITNESS FOR PURPOSE, EXISTENCE, QUALITY, SAFETY OR
            LEGALITY OF ITEMS AVAILABLE VIA THE SERVICES; OR (B) THE ABILITY OF SELLERS TO
            SELL ITEMS OR OF BUYERS TO PAY FOR ITEMS.<b>&nbsp;</b>IF THERE IS A DISPUTE
            INVOLVING ONE OR MORE USERS, SUCH USERS AGREE TO RESOLVE SUCH DISPUTE BETWEEN
            THEMSELVES DIRECTLY AND, TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW,
            RELEASE PINOY ELECTRONIC STORE ONLINE (PESO) AND ITS AFFILIATES FROM ANY AND
            ALL CLAIMS, DEMANDS AND DAMAGES ARISING OUT OF OR IN CONNECTION WITH ANY SUCH
            DISPUTE.<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><b><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>22.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EXCLUSIONS
            AND LIMITATIONS OF LIABILITY</span></b></span><span style='mso-bookmark:undefined'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>22.1&nbsp;&nbsp;&nbsp;&nbsp;
            TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, IN NO EVENT SHALL PINOY
            ELECTRONIC STORE ONLINE (PESO) BE LIABLE WHETHER IN CONTRACT, WARRANTY, TORT
            (INCLUDING, WITHOUT LIMITATION, NEGLIGENCE (WHETHER ACTIVE, PASSIVE OR
            IMPUTED), PRODUCT LIABILITY, STRICT LIABILITY OR OTHER THEORY), OR OTHER CAUSE
            OF ACTION AT LAW, IN EQUITY, BY STATUTE OR OTHERWISE, FOR:<o:p></o:p></span></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='mso-bookmark:undefined'><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>(<span class=SpellE>i</span>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            (A) LOSS OF USE; (B) LOSS OF PROFITS; (C) LOSS OF REVENUES; (D) LOSS OF DATA;
            (E) LOSS OF GOOD WILL; OR (F) FAILURE TO REALISE ANTICIPATED SAVINGS, IN EACH
            CASE WHETHER DIRECT OR INDIRECT; OR</span></span><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:56.15pt;text-align:justify;text-indent:-27.8pt'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(ii)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            ANY INDIRECT, INCIDENTAL, SPECIAL OR CONSEQUENTIAL DAMAGES, ARISING OUT OF OR
            IN CONNECTION WITH THE USE OR INABILITY TO USE THIS SITE OR THE SERVICES,
            INCLUDING, WITHOUT LIMITATION, ANY DAMAGES RESULTING THEREFROM, EVEN IF PINOY
            ELECTRONIC STORE ONLINE (PESO) HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH
            DAMAGES.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>22.2&nbsp;&nbsp;&nbsp;&nbsp; YOU ACKNOWLEDGE
            AND AGREE THAT YOUR ONLY RIGHT WITH RESPECT TO ANY PROBLEMS OR DISSATISFACTION
            WITH THE SERVICES IS TO REQUEST FOR TERMINATION OF YOUR ACCOUNT AND/OR
            DISCONTINUE ANY USE OF THE SERVICES.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>22.3&nbsp;&nbsp;&nbsp;&nbsp; IF,
            NOTWITHSTANDING THE PREVIOUS SECTIONS, PINOY ELECTRONIC STORE ONLINE (PESO) IS
            FOUND BY A COURT OF COMPETENT JURISDICTION TO BE LIABLE (INCLUDING FOR GROSS
            NEGLIGENCE), THEN, TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, ITS
            LIABILITY TO YOU OR TO ANY THIRD PARTY IS LIMITED TO THE LESSER OF: (A) ANY
            AMOUNTS DUE AND PAYABLE TO YOU PURSUANT TO THE PINOY ELECTRONIC STORE ONLINE (PESO)
            GUARANTEE; AND (B) SG $100 (ONE HUNDRED SINGAPORE DOLLARS).<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>22.4&nbsp;&nbsp;&nbsp;&nbsp; NOTHING IN THESE
            TERMS OF SERVICE SHALL LIMIT OR EXCLUDE ANY LIABILITY FOR DEATH OR PERSONAL
            INJURY CAUSED BY PINOY ELECTRONIC STORE ONLINE (PESO)’S NEGLIGENCE, FOR FRAUD
            OR FOR ANY OTHER LIABILITY ON THE PART OF PINOY ELECTRONIC STORE ONLINE (PESO)
            THAT CANNOT BE LAWFULLY LIMITED AND/OR EXCLUDED.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>23.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LINKS
            TO THIRD PARTY SITES</span></b><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>Third
            party links provided throughout the Site will let you leave this Site. These
            links are provided as a courtesy only, and the sites they link to are not under
            the control of Pinoy Electronic Store Online (PESO) in any manner whatsoever
            and you therefore access them at your own risk. Pinoy Electronic Store Online (PESO)
            is in no manner responsible for the contents of any such linked site or any
            link contained within a linked site, including any changes or updates to such
            sites. Pinoy Electronic Store Online (PESO) is providing these links merely as
            a convenience, and the inclusion of any link does not in any way imply or
            express affiliation, endorsement or sponsorship by Pinoy Electronic Store
            Online (PESO) of any linked site and/or any of its content therein.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>24.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;YOUR
            CONTRIBUTIONS TO THE SERVICES</span></b><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>24.1&nbsp;&nbsp;&nbsp;&nbsp; By submitting
            Content for inclusion on the Services, you represent and warrant that you have
            all necessary rights and/or permissions to grant the licenses below to Pinoy
            Electronic Store Online (PESO). You further acknowledge and agree that you are
            solely responsible for anything you post or otherwise make available on or
            through the Services, including, without limitation, the accuracy, reliability,
            nature, rights clearance, compliance with law and legal restrictions associated
            with any Content contribution. You hereby grant Pinoy Electronic Store Online (PESO)
            and its successors a perpetual, irrevocable, worldwide, non-exclusive,
            royalty-free, sub-licensable and transferable license to use, copy, distribute,
            republish, transmit, modify, adapt, create derivative works of, publicly
            display, and publicly perform such Content contribution on, through or in
            connection with the Services in any media formats and through any media
            channels, including, without limitation, for promoting and redistributing part
            of the Services (and its derivative works) without need of attribution and you
            agree to waive any moral rights (and any similar rights in any part of the
            world) in that respect. You understand that your contribution may be
            transmitted over various networks and changed to conform and adapt to technical
            requirements.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>24.2&nbsp;&nbsp;&nbsp;&nbsp; Any material,
            information or idea you post on or through the Services, or otherwise transmit
            to Pinoy Electronic Store Online (PESO) by any means (each, a
            &quot;Submission&quot;), is not considered confidential by Pinoy Electronic
            Store Online (PESO) and may be disseminated or used by Pinoy Electronic Store
            Online (PESO) without compensation or liability to you for any purpose
            whatsoever, including, but not limited to, developing, manufacturing and
            marketing products. By making a Submission to Pinoy Electronic Store Online (PESO),
            you acknowledge and agree that Pinoy Electronic Store Online (PESO) and/or
            other third parties may independently develop software, applications,
            interfaces, products and modifications and enhancements of the same which are
            identical or similar in function, code or other characteristics to the ideas
            set out in your Submission. Accordingly, you hereby grant Pinoy Electronic
            Store Online (PESO) and its successors a perpetual, irrevocable, worldwide,
            non-exclusive, royalty-free, sub-licensable and transferable license to develop
            the items identified above, and to use, copy, distribute, republish, transmit,
            modify, adapt, create derivative works of, publicly display, and publicly
            perform any Submission on, through or in connection with the Services in any
            media formats and through any media channels, including, without limitation,
            for promoting and redistributing part of the Services (and its derivative
            works). This provision does not apply to personal information that is subject
            to our privacy policy except to the extent that you make such personal
            information publicly available on or through the Services.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>25.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;THIRD
            PARTY CONTRIBUTIONS TO THE SERVICES AND EXTERNAL LINKS</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>25.1&nbsp;&nbsp;&nbsp;&nbsp; Each contributor
            to the Services of data, text, images, sounds, video, software and other
            Content is solely responsible for the accuracy, reliability, nature, rights
            clearance, compliance with law and legal restrictions associated with their
            Content contribution. As such, Pinoy Electronic Store Online (PESO) is not
            responsible to, and shall not, regularly monitor or check for the accuracy,
            reliability, nature, rights clearance, compliance with law and legal
            restrictions associated with any contribution of Content. You will not hold Pinoy
            Electronic Store Online (PESO) responsible for any User's actions or inactions,
            including, without limitation, things they post or otherwise make available via
            the Services.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>25.2&nbsp;&nbsp;&nbsp;&nbsp; In addition, the
            Services may contain links to third party products, websites, services and
            offers. These third party links, products, websites and services are not owned
            or controlled by Pinoy Electronic Store Online (PESO). Rather, they are
            operated by, and are the property of, the respective third parties, and may be
            protected by applicable copyright or other intellectual property laws and
            treaties. Pinoy Electronic Store Online (PESO) has not reviewed, and assumes no
            responsibility for the content, functionality, security, services, privacy
            policies, or other practices of these third parties. You are encouraged to read
            the terms and other policies published by such third parties on their websites
            or otherwise. By using the Services, you agree that Pinoy Electronic Store
            Online (PESO) shall not be liable in any manner due to your use of, or
            inability to use, any website or widget. You further acknowledge and agree that
            Pinoy Electronic Store Online (PESO) may disable your use of, or remove, any
            third party links, or applications on the Services to the extent they violate
            these Terms of Service.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>26.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;YOUR
            REPRESENTATIONS AND WARRANTIES</span></b><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>You
            represent and warrant that:<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            you possess the legal capacity (and in the case of a minor, valid parent or
            legal guardian consent), right and ability to enter into these Terms of Service
            and to comply with its terms; and<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:2.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            you will use the Services for lawful purposes only and in accordance with these
            Terms of Service and all applicable laws, rules, codes, directives, guidelines,
            policies and regulations.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>27.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INDEMNITY</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>You
            agree to indemnify, defend and hold harmless Pinoy Electronic Store Online (PESO),
            and its shareholders, subsidiaries, affiliates, directors, officers, agents,
            co-branders or other partners, and employees (collectively, the
            &quot;Indemnified Parties&quot;) from and against any and all claims, actions,
            proceedings, and suits and all related liabilities, damages, settlements,
            penalties, fines, costs and expenses (including, without limitation, any other
            dispute resolution expenses) incurred by any Indemnified Party arising out of
            or relating to: (a) any transaction made on the Site, or any dispute in
            relation to such transaction (except where Pinoy Electronic Store Online (PESO)
            or its Affiliates is the Seller in the transaction that the dispute relates
            to), (b) the Pinoy Electronic Store Online (PESO) Guarantee, (c) the hosting,
            operation, management and/or administration of the Services by or on behalf of Pinoy
            Electronic Store Online (PESO), (d) your violation or breach of any term of
            these Terms of Service or any policy or guidelines referenced herein, (e) your
            use or misuse of the Services, or (f) your breach of any law or any rights of a
            third party.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>28.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SEVERABILITY</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>If
            any provision of these Terms of Service shall be deemed unlawful, void, or for
            any reason unenforceable under the law of any jurisdiction, then that provision
            shall be deemed severable from these terms and conditions and shall not affect
            the validity and enforceability of any remaining provisions in such
            jurisdiction nor the validity and enforceability of the provision in question
            under the law of any other jurisdiction.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>29.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GOVERNING
            LAW</span></b><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'>These
            Terms of Service shall be governed by and construed in accordance with the laws
            of the Republic of Singapore without regard to its conflict of law rules. The
            United Nations Convention on Contracts for the International Sale of Goods and
            the Uniform Computer Information Transaction Act, to the extent applicable, are
            expressly disclaimed. Unless otherwise required by applicable laws, any
            dispute, controversy, claim or difference of any kind whatsoever shall arising
            out of or relating to these Terms of Service against or relating to Pinoy
            Electronic Store Online (PESO) or any Indemnified Party under these Terms of
            Service shall be referred to and finally resolved by arbitration in Singapore
            in accordance with the Arbitration Rules of the Singapore International
            Arbitration Centre (“SIAC Rules”) for the time being in force, which rules are
            deemed to be incorporated by reference in this Section. There will be one (1)
            arbitrator and the language of the arbitration shall be English.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>30.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GENERAL
            PROVISIONS</span></b><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>30.1&nbsp;&nbsp;&nbsp;&nbsp; Pinoy Electronic
            Store Online (PESO) reserves all rights not expressly granted herein.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>30.2&nbsp;&nbsp;&nbsp;&nbsp; Pinoy Electronic
            Store Online (PESO) may modify these Terms of Service at any time by posting
            the revised Terms of Service on this Site. Your continued use of this Site
            after such changes have been posted shall constitute your acceptance of such
            revised Terms of Service.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>30.3&nbsp;&nbsp;&nbsp;&nbsp; You may not
            assign, sublicense or transfer any rights granted to you hereunder or
            subcontract any of your obligations.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>30.4&nbsp;&nbsp;&nbsp;&nbsp; Nothing in these
            Terms of Service shall constitute a partnership, joint venture or
            principal-agent relationship between you and Pinoy Electronic Store Online (PESO),
            nor does it authorize you to incur any costs or liabilities on Pinoy Electronic
            Store Online (PESO)’s behalf.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>30.5&nbsp;&nbsp;&nbsp;&nbsp; The failure of Pinoy
            Electronic Store Online (PESO) at any time or times to require performance of
            any provision hereof shall in no manner affect its right at a later time to
            enforce the same unless the same is waived in writing.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>30.6&nbsp;&nbsp;&nbsp;&nbsp; These Terms of
            Service are solely for your and our benefit and are not for the benefit of any
            other person or entity, except for Pinoy Electronic Store Online (PESO)'s
            affiliates and subsidiaries (and each of Pinoy Electronic Store Online (PESO)'s
            and its affiliates' and subsidiaries' respective successors and assigns).<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>30.7&nbsp;&nbsp;&nbsp;&nbsp; The terms set
            forth in these Terms of Service and any agreements and policies included or
            referred to in these Terms of Service constitute the entire agreement and
            understanding of the parties with respect to the Services and the Site and
            supersede any previous agreement or understanding between the parties in
            relation to such subject matter. The parties also hereby exclude all implied
            terms in fact. In entering into the agreement formed by these Terms of Service,
            the parties have not relied on any statement, representation, warranty,
            understanding, undertaking, promise or assurance of any person other than as
            expressly set out in these Terms of Service. Each party irrevocably and
            unconditionally waives all claims, rights and remedies which but for this
            Section it might otherwise have had in relation to any of the foregoing. These
            Terms of Service may not be contradicted, explained or supplemented by evidence
            of any prior agreement, any contemporaneous oral agreement or any consistent
            additional terms.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>30.8&nbsp;&nbsp;&nbsp;&nbsp; If you have any
            questions or concerns about these Terms of Service or any issues raised in
            these Terms of Service or on the Site, please contacts us at:&nbsp;</span><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#428BCA'>support@pinoyelectronicstore.com</span><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'> <o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify;text-indent:-1.0cm'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>LEGAL NOTICES</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>: Please send all legal notices to&nbsp;</span><a
            href='mailto:legal@pinoyelectronicstore.com'><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman''>legal@pinoyelectronicstore.com</span></a><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;and Attention it to the “General
            Counsel”.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-top:0cm;margin-right:0cm;margin-bottom:7.5pt;
            margin-left:1.0cm;text-align:justify'><span style='font-size:10.5pt;font-family:
            'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;text-align:justify'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>I HAVE READ THIS AGREEMENT AND AGREE TO ALL OF
            THE PROVISIONS CONTAINED ABOVE AND ANY REVISION THE SAME HEREAFTER. BY CLICKING
            THE “SIGN UP ” OR “CONNECT WITH FACEBOOK” BUTTON BELOW, I UNDERSTAND THAT I AM
            CREATING A DIGITAL SIGNATURE, WHICH I INTEND TO HAVE THE SAME FORCE AND EFFECT
            AS IF I HAD SIGNED MY NAME MANUALLY.<o:p></o:p></span></p>
            
            <p class=MsoNormal><span style='font-family:'Times New Roman',serif;mso-fareast-font-family:
            'Times New Roman''><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal><o:p>&nbsp;</o:p></p>
            
            </div>"
            
            ))
        );
    }
    public function getPrivacyPolicy(){
        return array(
            'title' => html_entity_decode(utf8_decode("Privacy Policy")),
            'description' => html_entity_decode(utf8_decode("<div class='WordSection1'>

            <p class=MsoNormal align=center style='text-align:center;background:white'><b><span
            style='font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'><o:p></o:p></span></b></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>1. INTRODUCTION</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>1.1 Welcome to the PESO - Pinoy Electronic
            Store Online platform run by PC Vill,Inc. and its affiliates and affiliates
            (individually and collectively, &quot;PESO - Pinoy Electronic Store Online&quot;,
            &quot;we&quot;, &quot;us&quot; or &quot;our&quot;). PESO - Pinoy Electronic
            Store Online takes its responsibilities under applicable privacy laws and
            regulations (&quot;Privacy Laws&quot;) seriously and is committed to respecting
            the privacy rights and concerns of all Users of our PESO - Pinoy Electronic
            Store Online website (the &quot;Site&quot;) (we refer to the Site and the
            services we provide as described in our Site collectively as the
            &quot;Services&quot;). We recognize the importance of the personal data you
            have entrusted to us and believe that it is our responsibility to properly
            manage, protect and process your personal data. This Privacy Policy (“Privacy
            Policy” or “Policy”) is designed to assist you in understanding how we collect,
            use, disclose and/or process the personal data you have provided to us and/or
            we possess about you, whether now or in the future, as well as to assist you in
            making an informed decision before providing us with any of your personal data.
            Please read this Privacy Policy carefully. If you have any questions regarding
            this information or our privacy practices, please see the section entitled
            &quot;Questions, Concerns or Complaints? Contact Us&quot; at the end of this
            Privacy Policy.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>1.2 &quot;Personal Data&quot; or
            &quot;personal data&quot; means data, whether true or not, about an individual
            who can be identified from that data, or from that data and other information
            to which an organisation has or is likely to have access. Common examples of
            personal data could include name, identification number and contact
            information.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>1.3 By using the Services, registering for an
            account with us, visiting our website, or accessing the Services, you
            acknowledge and agree that you accept the practices, requirements, and/or
            policies outlined in this Privacy Policy, and you hereby consent to us
            collecting, using, disclosing and/or processing your personal data as described
            herein. IF YOU DO NOT CONSENT TO THE PROCESSING OF YOUR PERSONAL DATA AS
            DESCRIBED IN THIS PRIVACY POLICY, PLEASE DO NOT USE OUR SERVICES OR ACCESS OUR
            WEBSITE. If we change our Privacy Policy, we will post those changes or the
            amended Privacy Policy on our website. We reserve the right to amend this
            Privacy Policy at any time.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>2. WHEN WILL PESO - PINOY ELECTRONIC STORE
            ONLINE COLLECT PERSONAL DATA?</span></b><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>2.1 We will/may collect personal data about
            you:&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(a) when you register and/or use our Services
            or Site, or open an account with us;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(b) when you submit any form, including, but
            not limited to, application forms or other forms relating to any of our
            products and services, whether online or by way of a physical form; (c) when
            you enter into any agreement or provide other documentation or information in
            respect of your interactions with us, or when you use our products and
            services;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(d) when you interact with us, such as via
            telephone calls (which may be recorded), letters, fax, face-to-face meetings,
            social media platforms and emails;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(e) when you use our electronic services, or
            interact with us via our application or use services on our website. This
            includes, without limitation, through cookies which we may deploy when you
            interact with our application or website;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(f) when you carry out transactions through
            our Services;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(g) when you provide us with feedback or
            complaints;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(h) when you register for a contest; or&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(i) when you submit your personal data to us
            for any reason.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>The above does not purport to be exhaustive
            and sets out some common instances of when personal data about you may be
            collected.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>2.2 When you visit, use or interact with our
            mobile application or the Site, we may collect certain information by automated
            or passive means using a variety of technologies, which technologies may be
            downloaded to your device and may set or modify settings on your device. The
            information we collect may include, without limitation, your Internet Protocol
            (IP) address, computer/mobile device operating system and browser type, type of
            mobile device, the characteristics of the mobile device, the unique device
            identifier (UDID) or mobile equipment identifier (MEID) for your mobile device,
            the address of a referring web site (if any), and the pages you visit on our
            website and mobile applications and the times of visit. We may collect, use
            disclose and/or process this information only for the Purposes (defined
            below).&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>2.3 Our mobile applications may collect
            precise information about the location of your mobile device using technologies
            such as GPS, Wi-Fi, etc.. We collect, use, disclose and/or process this
            information for one or more Purposes including, without limitation,
            location-based services that you request or to deliver relevant content to you
            based on your location or to allow you to share your location to other Users as
            part of the services under our mobile applications. For most mobile devices,
            you are able to withdraw your permission for us to acquire this information on
            your location through your device settings. If you have questions about how to
            disable your mobile device's location services, please contact your mobile
            device service provider or the device manufacturer.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>3. WHAT PERSONAL DATA WILL PESO - PINOY
            ELECTRONIC STORE ONLINE COLLECT?</span></b><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>3.1 The personal data that PESO - Pinoy
            Electronic Store Online may collect includes but is not limited to:&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>• name;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>• email address;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>• date of birth;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>• billing address;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>• bank account and payment information;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>• telephone number;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>• gender;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>• any other information about the User when
            the User signs up to use our Services or website, and when the User uses the
            Services or website, as well as information related to how the User uses our
            Services or website; and&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>• aggregate data on content the User engages
            with.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>3.2 If you do not want us to collect the
            aforementioned information/personal data, you may opt out at any time by
            notifying our Data Protection Officer in writing about it. Further information
            on opting out can be found in the section below entitled &quot;How can you
            opt-out, remove, request access to or modify information you have provided to
            us?&quot; . Note, however, that opting out of us collecting your personal data
            or withdrawing your consent for us to collect, use or process your personal
            data may affect your use of the Services. For example, opting out of the
            collection of location information will cause its location-based features to be
            disabled.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>4. SETTING UP AN ACCOUNT</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>In order to use certain functionalities of the
            Services, you will have to create a user account which requires you to submit
            certain personal data. When you register and create an account, we require you
            to provide us with your name and email address as well as a user name that you
            select. We also ask for certain information about yourself such as your
            telephone number, email address, shipping address, photo identification, bank
            account details, age, date of birth, gender and interests. Upon activating an
            account, you will select a user name and password. Your user name and password
            will be used so you can securely access and maintain your account.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>5. VIEWING WEB PAGES</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>As with most websites, your computer sends
            information which may include personal data about you that gets logged by a web
            server when you browse our Site. This typically includes without limitation
            your computer's IP address, operating system, browser name/version, the
            referring web page, requested page, date/time, and sometimes a
            &quot;cookie&quot; (which can be disabled using your browser preferences) to
            help the site remember your last visit. If you are logged in, this information
            is associated with your personal account. The information is also included in
            anonymous statistics to allow us to understand how visitors use our site.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>6. COOKIES</span></b><span style='font-size:
            10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>6.1 We may from time to time implement
            &quot;cookies&quot; or other features to allow us or third parties to collect
            or share information that will help us improve our Site and the Services we
            offer, or help us offer new services and features. “Cookies” are identifiers we
            transfer to your computer or mobile device that allow us to recognize your
            computer or device and tell us how and when the Services or website are used or
            visited, by how many people and to track movements within our website. We may
            link cookie information to personal data. Cookies also link to information
            regarding what items you have selected for purchase and pages you have viewed.
            This information is used to keep track of your shopping cart, for example.
            Cookies are also used to deliver content specific to your interest and to
            monitor website usage.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>6.2 You may refuse the use of cookies by
            selecting the appropriate settings on your browser. However, please note that
            if you do this you may not be able to use the full functionality of our Site or
            the Services.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>7. VIEWING AND DOWNLOADING CONTENT AND
            ADVERTISING</span></b><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>As with browsing web pages, when you watch
            content and advertising and access other software on our Site or through the
            Services, most of the same information is sent to us (including, without
            limitation, IP Address, operating system, etc.); but, instead of page views,
            your computer sends us information on the content, advertisement viewed and/or
            software installed by the Services and the website and time.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>8. COMMUNITY &amp; SUPPORT</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>8.1 We provide customer service support
            through email, SMS and feedback forms. In order to provide customer support, we
            will ask for your email address and mobile phone number. We only use
            information received from customer support requests, including, without
            limitation, email addresses, for customer support services and we do not
            transfer to or share this information with any third parties.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>8.2 You can also post questions and answer
            other User questions in our community forums. Our forum and messaging services
            allow you to participate in our community; to do so, we maintain information,
            such as your user ID, contact list and status messages. In addition, these and
            similar services in the future may require us to maintain your user ID and password.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>9. SURVEYS</span></b><span style='font-size:
            10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>From time-to-time, we may request information
            from Users via surveys. Participation in these surveys is completely voluntary
            and you therefore have a choice whether or not to disclose your information to
            us. Information requested may include, without limitation, contact information
            (such as your email address), and demographic information (such as interests or
            age level). Survey information will be used for the purposes of monitoring or
            improving the use and satisfaction of the Services and will not be transferred
            to third parties, other than our contractors who help us to administer or act
            upon the survey.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>10. HOW DO WE USE THE INFORMATION YOU PROVIDE
            US?</span></b><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>10.1 We may collect, use, disclose and/or
            process your personal data for one or more of the following purposes:&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(a) to consider and/or process your
            application/transaction with us or your transactions or communications with
            third parties via the Services;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(b) to manage, operate, provide and/or
            administer your use of and/or access to our Services and our website, as well
            as your relationship and user account with us;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(c) to manage, operate, administer and provide
            you with as well as to facilitate the provision of our Services, including,
            without limitation, remembering your preferences;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(d) to tailor your experience through the
            Services by displaying content according to your interests and preferences,
            providing a faster method for you to access your account and submit information
            to us and allowing us to contact you, if necessary;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(e) to respond to, process, deal with or
            complete a transaction and/or to fulfil your requests for certain products and
            services and notify you of service issues and unusual account actions;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(f) to enforce our Terms of Service or any
            applicable end user license agreements;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(g) to protect personal safety and the rights,
            property or safety of others;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(h) for identification and/or
            verification;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(i) to maintain and administer any software
            updates and/or other updates and support that may be required from time to time
            to ensure the smooth running of our Services;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(j) to deal with or facilitate customer
            service, carry out your instructions, deal with or respond to any enquiries
            given by (or purported to be given by) you or on your behalf;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(k) to contact you or communicate with you via
            voice call, text message and/or fax message, email and/or postal mail or
            otherwise for the purposes of administering and/or managing your relationship
            with us or your use of our Services, such as but not limited to communicating
            administrative information to you relating to our Services. You acknowledge and
            agree that such communication by us could be by way of the mailing of
            correspondence, documents or notices to you, which could involve disclosure of
            certain personal data about you to bring about delivery of the same as well as
            on the external cover of envelopes/mail packages;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(l) to inform you when another User has sent
            you a private message or posted a comment for you on the Site;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(m) to conduct research, analysis and
            development activities (including, but not limited to, data analytics, surveys,
            product and service development and/or profiling), to analyse how you use our
            Services, to improve our Services or products and/or to enhance your customer
            experience;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(n) to allow for audits and surveys to, among
            other things, validate the size and composition of our target audience, and
            understand their experience with PESO - Pinoy Electronic Store Online’s
            Services;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(o) where you give us your prior consent, for
            marketing and in this regard, to send you by various modes of communication
            such as postal mail, email, location-based services or otherwise, marketing and
            promotional information and materials relating to products and/or services
            (including, without limitation, products and/or services of third parties whom PESO
            - Pinoy Electronic Store Online may collaborate or tie up with) that PESO -
            Pinoy Electronic Store Online (and/or its affiliates or related corporations)
            may be selling, marketing or promoting, whether such products or services exist
            now or are created in the future. If you are in Philippine, In the case of the
            sending of marketing or promotional information to you by voice call, SMS/MMS
            or fax to your Philippine facsimile number, we will not do so unless we have
            complied with the requirements of Philippine’s Privacy Laws in relation to use
            of such latter modes of communication in sending you marketing information or
            you have expressly consented to the same;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(p) to respond to legal processes or to comply
            with or as required by any applicable law, governmental or regulatory
            requirements of any relevant jurisdiction, including, without limitation,
            meeting the requirements to make disclosure under the requirements of any law
            binding on PESO - Pinoy Electronic Store Online or on its related corporations
            or affiliates;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(q) to produce statistics and research for
            internal and statutory reporting and/or record-keeping requirements;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(r) to carry out due diligence or other
            screening activities (including, without limitation, background checks) in
            accordance with legal or regulatory obligations or our risk management
            procedures that may be required by law or that may have been put in place by
            us;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(s) to audit our Services or PESO - Pinoy
            Electronic Store Online's business;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(t) to prevent or investigate any fraud,
            unlawful activity, omission or misconduct, whether relating to your use of our
            Services or any other matter arising from your relationship with us, and
            whether or not there is any suspicion of the aforementioned;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(u) to store, host, back up (whether for
            disaster recovery or otherwise) of your personal data, whether within or
            outside of your jurisdiction;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(v) to deal with and/or facilitate a business
            asset transaction or a potential business asset transaction, where such
            transaction involves PESO - Pinoy Electronic Store Online as a participant or
            involves only a related corporation or affiliate of PESO - Pinoy Electronic
            Store Online as a participant or involves PESO - Pinoy Electronic Store Online
            and/or any one or more of PESO - Pinoy Electronic Store Online's related
            corporations or affiliates as participant(s), and there may be other third
            party organisations who are participants in such transaction. A “business asset
            transaction” refers to the purchase, sale, lease, merger, amalgamation or any
            other acquisition, disposal or financing of an organisation or a portion of an
            organisation or of any of the business or assets of an organisation;
            and/or&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(w) any other purposes which we notify you of
            at the time of obtaining your consent. (collectively, the “<b>Purposes</b>”).&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>10.2 As the purposes for which we will/may
            collect, use, disclose or process your personal data depend on the
            circumstances at hand, such purpose may not appear above. However, we will
            notify you of such other purpose at the time of obtaining your consent, unless
            processing of the applicable data without your consent is permitted by the
            Privacy Laws.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>11. SHARING OF INFORMATION FROM THE SERVICES</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>Our Services enable Users to share personal
            information with each other, in almost all occasions without PESO - Pinoy
            Electronic Store Online’s involvement, to complete transactions. In a typical
            transaction, Users may have access to each other’s name, user ID, email address
            and other contact and postage information. Our Terms of Service require that
            Users in possession of another User’s personal data (the “Receiving Party”)
            must (i) comply with all applicable Privacy Laws; (ii) allow the other User
            (the “Disclosing Party”) to remove him/herself from the Receiving Party’s
            database; and (iii) allow the Disclosing Party to review what information have
            been collected about them by the Receiving Party.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>12. HOW DOES PESO - PINOY ELECTRONIC STORE
            ONLINE PROTECT CUSTOMER INFORMATION?</span></b><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>We implement a variety of security measures to
            ensure the security of your personal data on our systems. User personal data is
            contained behind secured networks and is only accessible by a limited number of
            employees who have special access rights to such systems. We will retain
            personal data in accordance with the Privacy Laws and/or other applicable laws.
            That is, we will destroy or anonymize your personal data as soon as it is
            reasonable to assume that (i) the purpose for which that personal data was
            collected is no longer being served by the retention of such personal data; and
            (ii) retention is no longer necessary for any legal or business purposes. If
            you cease using the Site, or your permission to use the Site and/or the
            Services is terminated, we may continue storing, using and/or disclosing your
            personal data in accordance with this Privacy Policy and our obligations under
            the Privacy Laws. Subject to applicable law, we may securely dispose of your
            personal data without prior notice to you.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>13. DOES PESO - PINOY ELECTRONIC STORE ONLINE
            DISCLOSE THE INFORMATION IT COLLECTS FROM ITS VISITORS TO OUTSIDE PARTIES?</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>13.1 In conducting our business, we will/may
            need to disclose your personal data to our third party service providers,
            agents and/or our affiliates or related corporations, and/or other third
            parties, whether sited in Philippine or outside of Philippine, for one or more
            of the above-stated Purposes. Such third party service providers, agents and/or
            affiliates or related corporations and/or other third parties would be
            processing your personal data either on our behalf or otherwise, for one or
            more of the above-stated Purposes. Such third parties include, without
            limitation:&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(a) our subsidiaries, affiliates and related
            corporations;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(b) contractors, agents, service providers and
            other third parties we use to support our business. These include but are not
            limited to those which provide administrative or other services to us such as
            mailing houses, telecommunication companies, information technology companies
            and data centres;&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(c) a buyer or other successor in the event of
            a merger, divestiture, restructuring, reorganization, dissolution or other sale
            or transfer of some or all of PESO - Pinoy Electronic Store Online’s assets,
            whether as a going concern or as part of bankruptcy, liquidation or similar
            proceeding, in which personal data held by PESO - Pinoy Electronic Store Online
            about our Service Users is among the assets transferred; or to a counterparty
            in a business asset transaction that PESO - Pinoy Electronic Store Online or
            any of its affiliates or related corporations is involved in; and&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(d) third parties to whom disclosure by us is
            for one or more of the Purposes and such third parties would in turn be collecting
            and processing your personal data for one or more of the Purposes 13.2 This may
            require, among other things, share statistical and demographic information
            about our Users and their use of the Services with suppliers of advertisements
            and programming. This would not include anything that could be used to identify
            you specifically or to discover individual information about you.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>13.3 For the avoidance of doubt, in the event
            that Privacy Laws or other applicable laws permit an organisation such as us to
            collect, use or disclose your personal data without your consent, such
            permission granted by the laws shall continue to apply.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>13.4 Third parties may unlawfully intercept or
            access personal data transmitted to or contained on the site, technologies may
            malfunction or not work as anticipated, or someone might access, abuse or
            misuse information through no fault of ours. We will nevertheless deploy
            reasonable security arrangements to protect your personal data as required by
            the Privacy Laws; however there can inevitably be no guarantee of absolute
            security such as but not limited to when unauthorised disclosure arises from
            malicious and sophisticated hacking by malcontents through no fault of
            ours.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>14. INFORMATION ON CHILDREN</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>The Services are not intended for children
            under the age of 10. We do not knowingly collect or maintain any personal data
            or non-personally-identifiable information from anyone under the age of 10 nor
            is any part of our Site or other Services directed to children under the age of
            10. We will close any accounts used exclusively by such children and will
            remove and/or delete any personal data we believe was submitted by any child
            under the age of 10.<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>15. INFORMATION COLLECTED BY THIRD PARTIES</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>15.1 Our Site uses Google Analytics, a web
            analytics service provided by Google, Inc. (&quot;Google&quot;). Google
            Analytics uses cookies, which are text files placed on your computer, to help
            the website analyse how Users use the Site. The information generated by the
            cookie about your use of the website (including your IP address) will be
            transmitted to and stored by Google on servers in the United States. Google
            will use this information for the purpose of evaluating your use of the
            website, compiling reports on website activity for website operators and
            providing other services relating to website activity and Internet usage.
            Google may also transfer this information to third parties where required to do
            so by law, or where such third parties process the information on Google's
            behalf. Google will not associate your IP address with any other data held by
            Google.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>15.2 We, and third parties, may from time to
            time make software applications downloads available for your use on or through
            the Services. These applications may separately access, and allow a third party
            to view, your identifiable information, such as your name, your user ID, your
            computer's IP Address or other information such as any cookies that you may
            previously have installed or that were installed for you by a third party
            software application or website. Additionally, these applications may ask you
            to provide additional information directly to third parties. Third party
            products or services provided through these applications are not owned or
            controlled by PESO - Pinoy Electronic Store Online. You are encouraged to read
            the terms and other policies published by such third parties on their websites
            or otherwise.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>16. DISCLAIMER REGARDING SECURITY AND THIRD
            PARTY SITES&nbsp;</span></b><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>16.1 WE DO NOT GUARANTEE THE SECURITY OF
            PERSONAL DATA AND/OR OTHER INFORMATION THAT YOU PROVIDE ON THIRD PARTY SITES.
            We do implement a variety of security measures to maintain the safety of your
            personal data that is in our possession or under our control. Your personal
            data is contained behind secured networks and is only accessible by a limited
            number of persons who have special access rights to such systems, and are
            required to keep the personal data confidential. When you place orders or
            access your personal data, we offer the use of a secure server. All personal
            data or sensitive information you supply is encrypted into our databases to be
            only accessed as stated above.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>16.2 In an attempt to provide you with
            increased value, we may choose various third party websites to link to, and
            frame within, the Site. We may also participate in co-branding and other
            relationships to offer e-commerce and other services and features to our
            visitors. These linked sites have separate and independent privacy policies as
            well as security arrangements. Even if the third party is affiliated with us,
            we have no control over these linked sites, each of which has separate privacy
            and data collection practices independent of us. Data collected by our co-brand
            partners or third party web sites (even if offered on or through our Site) may
            not be received by us.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>16.3 We therefore have no responsibility or
            liability for the content, security arrangements (or lack thereof) and
            activities of these linked sites. These linked sites are only for your
            convenience and you therefore access them at your own risk. Nonetheless, we
            seek to protect the integrity of our Site and the links placed upon each of
            them and therefore welcome any feedback about these linked sites (including,
            without limitation, if a specific link does not work).&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>17. WILL PESO - PINOY ELECTRONIC STORE ONLINE
            TRANSFER YOUR INFORMATION OVERSEAS?</span></b><span style='font-size:10.5pt;
            font-family:'Arial',sans-serif;mso-fareast-font-family:'Times New Roman';
            color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>Your personal data and/or information may be
            transferred to, stored or processed outside of your country. In most cases,
            your personal data will be processed in Philippine, where our servers are located
            and our central database is operated. PESO - Pinoy Electronic Store Online will
            only transfer your information overseas in accordance with Privacy Laws. 18.
            HOW CAN YOU OPT-OUT, REMOVE, REQUEST ACCESS TO OR MODIFY INFORMATION YOU HAVE
            PROVIDED TO US?&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.1&nbsp;<i>Opting Out and Withdrawing
            Consent&nbsp;</i><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.1.1 To modify your email subscriptions,
            please let us know by sending an email to our Personal Data Protection Officer
            at the address listed below. Please note that due to email production
            schedules, you may still receive emails that are already in production.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.1.2 You may withdraw your consent for the
            collection, use and/or disclosure of your personal data in our possession or
            under our control by sending an email to our Personal Data Protection Officer
            at the email address listed below in Section 19.2.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.1.3 Once we have your clear withdrawal
            instructions and verified your identity, we will process your request for
            withdrawal of consent, and will thereafter not collect, use and/or disclose
            your personal data in the manner stated in your request. If we are unable to
            verify your identity or understand your instructions, we will liaise with you
            to understand your request.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.1.4 However, your withdrawal of consent
            could result in certain legal consequences arising from such withdrawal. In
            this regard, depending on the extent of your withdrawal of consent for us to
            process your personal data, it may mean that we will not be able to continue
            providing the Services to you, we may need to terminate your existing
            relationship and/or the contract you have with us, etc., as the case may be,
            which we will inform you of.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.2&nbsp;<i>Requesting Access and/or
            Correction of Personal Data&nbsp;</i><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.2.1 If you have an account with us, you may
            personally access and/or correct your personal data currently in our possession
            or control through the Account Settings page on the Site. If you do not have an
            account with us, you may request to access and/or correct your personal data
            currently in our possession or control by submitting a written request to us.
            We will need enough information from you in order to ascertain your identity as
            well as the nature of your request so as to be able to deal with your request.
            Hence, please submit your written request by sending an email to our Personal Data
            Protection Officer at the email address listed below in Section 19.2.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.2.2 For a request to access personal data,
            once we have sufficient information from you to deal with the request, we will
            seek to provide you with the relevant personal data within 30 days. Note that
            Privacy Laws may exempt certain types of personal data from being subject to
            your access request.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.2.3 For a request to correct personal data,
            once we have sufficient information from you to deal with the request, we will:<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(a) correct your personal data within 30 days.
            Note that Privacy Laws may exempt certain types of personal data from being
            subject to your correction request as well as provides for situation(s) when
            correction need not be made by us despite your request; and&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>(b) we will send the corrected personal data
            to every other organisation to which the personal data was disclosed by us
            within a year before the date the correction was made, unless that other
            organisation does not need the corrected personal data for any legal or
            business purpose.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.2.4 Notwithstanding sub-paragraph (b)
            immediately above, we may, if you so request, send the corrected personal data
            only to specific organisations to which the personal data was disclosed by us
            within a year before the date the correction was made.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.2.5 We will/may also be charging you a
            reasonable fee for the handling and processing of your requests to access your
            personal data. If we so choose to charge, we will provide you with a written
            estimate of the fee we will be charging. Please note that we are not required
            to respond to or deal with your access request unless you have agreed to pay
            the fee.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>18.2.6 We reserve the right to refuse to
            correct your personal data in accordance with the provisions as set out in
            Privacy Laws, where they require and/or entitle an organisation to refuse to
            correct personal data in stated circumstances.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>19. QUESTIONS, CONCERNS OR COMPLAINTS? CONTACT
            US&nbsp;</span></b><span style='font-size:10.5pt;font-family:'Arial',sans-serif;
            mso-fareast-font-family:'Times New Roman';color:#535258'><o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>19.1 If you have any questions or concerns
            about our privacy practices or your dealings with the Services, please do not
            hesitate to contact support@pinoyelectronicstore.com.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>19.2 If you have any complaint or grievance
            regarding how we are handling your personal data or about how we are complying
            with Privacy Laws, we welcome you to contact us with your complaint or
            grievance. Please contact us through email with your complaint or grievance:
            E-mail: admin@pinoyelectronicstore.com and Attention it to the &quot;Personal
            Data Protection Officer&quot;. Please send all legal notices to
            legal@pinoyelectronicstore.com and Attention it to the “General Counsel”.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>19.3 Where it is an email or a letter through
            which you are submitting a complaint, your indication at the subject header
            that it is a Privacy Law complaint would assist us in attending to your
            complaint speedily by passing it on to the relevant staff in our organisation
            to handle. For example, you could insert the subject header as “Privacy
            Complaint”. We will certainly strive to deal with any complaint or grievance
            that you may have fairly and as soon as possible.&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'><o:p>&nbsp;</o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>20. TERMS AND CONDITIONS</span></b><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>&nbsp;<o:p></o:p></span></p>
            
            <p class=MsoNormal style='margin-bottom:7.5pt;background:white'><span
            style='font-size:10.5pt;font-family:'Arial',sans-serif;mso-fareast-font-family:
            'Times New Roman';color:#535258'>Please also read the Terms of Service
            establishing the use, disclaimers, and limitations of liability governing the
            use of the Site and the Services and other related policies.<o:p></o:p></span></p>
            
            <p class=MsoNormal><o:p>&nbsp;</o:p></p>
            
            </div>
            "))
            );
    }
}
$information = new Information();