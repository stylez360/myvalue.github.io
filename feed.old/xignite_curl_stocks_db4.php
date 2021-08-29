<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include "dbconnect.php";

$string = "ABLT,APBL,ACU,ACY,AE,AUSPF,AER,ATPL,AIA,BZ,AIJ,AIK,AIM,HAIPF,AMPLQ,ALI,ADGI,ALUHQ,AMI,AMK,TRLI,AMS,GAMR,AMWT,ANA,ANDR,ANE,ANH,ATNI,API,APO,ASEI,ESYS,ATCV,ATPIF,IMJX,ATX,AVD,AMAG,AWX,AMPXQ,PCFO,BAL,BCL,BCPC,BDLN,BDL,BDR,BET,BFD,BHB,BHC,BHODQ,BRTR,BKC,BKR,BL,BLD,BLE,BMI,BDLSQ,BNP,BNT,BRCOQ,BRN,BTI,BOLT,BTX,BVAH,BVB,BWN,BXLC,BYS,BYX,ELC,CAC,APYI,CAS,CBJ,CFSB,CBN,CCF,CDV,CEF,CFK,CFS,CGFW,THNKQ,CLNHD,CIA,CID,CLQ,CLT,CMT,CTON,CNVT,CARV,CBRX,CPHJ,CPTP,CRV,CTO,CTTC,CHADQ,CMTY,CUB,CVBF,CVC,CVR,CXIA,NXY,CYBI,CPBK,DAR,CDDT,CVA,DIA,DINIQ,DIOD,DIT,DLI,DEPO,SNGX,DPW,DRHA,DRS,DVN,DW,DWL,DXR,DXT,BAM,EBI,ECO,EE,EECPQ,EPRTF,EEI,EFC,EGY,EMLIF,EML,BMTL,ERS,ESC,ESP,ESX,ETCC,ETZLF,ELSF,EXMCQ,FAB,FAH,FAJ,FCB,FCN,FEIM,FITN,PSTX,FFPM,SSB,FPU,FRD,FRS,FSM,FSNN,OTVLQ,OIVO,FWV,GAF,GAN,GBR,GBTI,GCR,INO,GHM,GRC,GRI,PTX,GVE,GVP,GW,GXYF,HIR,GWSV,HRT,INB,JOB,KMLC,CRYFQ,LLB,MWH,RPD,SCB,APXSQ,SIM,SRS,SGMS,CCMTF,XLG,BCYRA,EEM,EFM,FIZZ,HA,HAI,WAC,HCT,ACUR,HEB,HFD,HH,HII,ULEIQ,HLMD,HGMG,HMG,HMP,HNV,HFC,HOV,HRY,HSI,HSR,HT,INVHY,HDTCQ,HTLLQ,HWD,HWS,HZP,ICH,ICL,ONSE,IDH,ENG,IDNX,IFCJ,IG,IGLPA,INGA,IH,IHT,ILI,ILXRQ,IMGC,IMH,IMO,INS,IOI,IOT,IOX,IRIS,INSV,IVX,JAZ,MYR,JET,JCLY,JOL,JST,JWG,KBKC,CRO,KEA,KFI,EVEIQ,NGAC,KTMC,CFCB,KPG,SSY,KTR,KYF,LAN,LB,LDR,LGF,LGL,LKII,LTS,LVC,MAB,MAJJ,MAM,MYAG,MPB,MCX,MDB,MDY,RELAF,MGGP,MGP,MHR,MLF,MLH,MLN,MLP,MMD,MTRM,UNCAQ,MMX,MRFD,MRM,MRS,MLSS,MSA,MSL,MEAS,MSWP,MTT,MTYG,VPF,MAXX,MYE,NAT,NBN,NBR,NBT,NEPF,NER,NHC,NHR,NMC,NVAX,NSO,NTIC,NTN,NVR,NYF,LIPD,OHBIQ,OLP,OPC,ORCI,ORG,OSE,OSH,OTF,OTR,PABK,KW,CMCT,TPC,PDM,PDR,ILNC,PEX,PGP,RNST,PHX,PLCSF,PCLB,PLI,PLM,PLX,PMC,PMD,SAMB,PCMC,PRK,PSB,PORT,PTN,PW,PWX,PZA,QCOR,RAS,RBC,RBKV,RCF,RCO,VBR,REF,VRXMF,RH,RHT,RIC,RVHLQ,RJI,RMI,ROG,ROM,RPI,RIVT,RUM,SLVR,TOX.H,YCS,ILAS,AJX,ASB,BIN,ESHB,SRSK,NYMH,GMLI,SECA,SAL,SBBX,SCPJ,SCYL,SEB,MXBTF,SVSSF,IFMI,SFTC,SGA,SGB,SGC,SHDBQ,SHFK,SYN,SIF,SJW,SKK,IMA,IIN,SNR,SOSO,SPN,SPY,SRK,SRNN,SDGO,SSN,STBC,STS,AVSO,STZ,RTPRQ,SVT,SWC,SYC,SYBT,SZBI,TALN,TAM,TBA,TBP,TCA,TCK,TDS,TFG,TFH,TLVA,KAI,THKY,OSRS,NYIN,RUM,TLLEQ,TNLX,TMG,TISI,TMP,SEV,TOF,TPC,TPY,TRT,TRV,SPCO,TSH,TTNP,TWA,AHCI,TXM,UG,UMH,UNI,UPX,UQM,USLQ,USM,UTI,UTL,UXP,VCR,VDC,VII,VIRC,VSIH,VLG,VOOC,CRVP,VS,VFPI,VSR,VULC,WEBC,WELX,WFI,WGA,WEDC,WIR,WIX,WLC,REIS";
$pp_string = '_Token=EF2662FA141B4DC086F6A72B2D15AD2C&IdentifierType=Symbol&Identifiers='.$string;
$opts = array('http'=>
        array('method' =>'POST',
                'port' =>'443',
                'header' =>'Content-type: application/x-www-form-urlencoded',
                'content' =>$pp_string
                )
			);
//print_r($opts);
$context = stream_context_create($opts);
$file = fopen('http://globalquotes.xignite.com/v3/xGlobalQuotes.json/GetGlobalDelayedQuotes', 'rb', false, $context) or die ("Merchant Services Not Responding");
$results = @stream_get_contents($file);
/*$results=str_replace(' ','+',$results);
$pairs = explode('&',$results);
foreach($pairs as $value)
	{
	//print $value.'<br><br><br>';
	$pair = explode('=', $value);
	//print_r($pair);
	foreach($pair as $key => $value)
		{
		$$pair['0'] = $pair['1'];
		}
	}*/
//Need to trim the last } off the string as it doesnt have a , after it and therefore does not get removed in the explode() thus the -2 in the substr()
//Also needed to trim the [ and ] from the front and back of the package handed to us from the API
$results = substr($results, 1, -2);
/*print "<pre>";
print $results;
print "</pre>";*/


$json = explode("},", $results);
//$json[0] = $json[0]."}";
foreach($json as $key => $value)
    {
    print $value."<br>";
    $value = $value."}";
    $obj = json_decode($value, TRUE);
    print "<pre>";
    print_r($obj);
    print "</pre>";
    print "<pre>";
    var_dump(json_decode($value));
    print "</pre>";
    foreach($obj as $key => $value)
            {
            $$key = $value;
            }
    foreach($obj['Security'] as $key => $value)
            {
            $$key = $value;
            }
    //$CIK = $obj['Security']['CIK'];
    print "###############".$Identity."#####################";
    $feed_insert = "INSERT INTO `feed_data`.`stock_feed` (
            `uid`,
            `Outcome`,
            `Message`,
            `Identity`,
            `Delay`,
            `Date`,
            `Time`,
            `UTCOffset`,
            `Open`,
            `Close`,
            `High`,
            `Low`,
            `Last`,
            `LastSize`,
            `Volume`,
            `PreviousClose`,
            `PreviousCloseDate`,
            `ChangeFromPreviousClose`,
            `PercentChangeFromPreviousClose`,
            `Bid`,
            `BidSize`,
            `BidDate`,
            `BidTime`,
            `Ask`,
            `AskSize`,
            `AskDate`,
            `AskTime`,
            `High52Weeks`,
            `Low52Weeks`,
            `Currency`,
            `TradingHalted`,
            `CIK`,
            `CUSIP`,
            `Symbol`,
            `ISIN`,
            `Valoren`,
            `Name`,
            `Market`,
            `MarketIdentificationCode`,
            `MostLiquidExchange`,
            `CategoryOrIndustry`)
            VALUES (
            '',
            '".$Outcome."',
            '".$Message."',
            '".$Identity."',
            '".$Delay."',
            '".$Date."',
            '".$Time."',
            '".$UTCOffset."',
            '".$Open."',
            '".$Close."',
            '".$High."',
            '".$Low."',
            '".$Last."',
            '".$LastSize."',
            '".$Volume."',
            '".$PreviousClose."',
            '".$PreviousCloseDate."',
            '".$ChangeFromPreviousClose."',
            '".$PercentChangeFromPreviousClose."',
            '".$Bid."',
            '".$BidSize."',
            '".$BidDate."',
            '".$BidTime."',
            '".$Ask."',
            '".$AskSize."',
            '".$AskDate."',
            '".$AskTime."',
            '".$High52Weeks."',
            '".$Low52Weeks."',
            '".$Currency."',
            '".$TradingHalted."',
            '".$CIK."',
            '".$CUSIP."',
            '".$Symbol."',
            '".$ISIN."',
            '".$Valoren."',
            '".$Name."',
            '".$Market."',
            '".$MarketIdentificationCode."',
            '".$MostLiquidExchange."',
            '".$CategoryOrIndustry."'
            );";
    print '<br><br>'.$feed_insert.'<br><br>';
    mysqli_query($data_link, $feed_insert);
    print "<br>-----------------------------------------------------------------------------------------------------<br>";
    }
?>
