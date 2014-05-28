<?php

class Profanity {

    public $full;
    public $any;
    public $text;

    public function __construct($text = null) {
        $this->full = explode(',', "ass,asslick,asses,asshole,assholes,asskisser,asswipe,balls,bastard,beastial,beastiality,beastility,bellywhacker,bestial,bestiality,bitch,bitcher,bitchers,bitches,bitchin,bitching,blowjob,blowjobs,bonehead,boner,browneye,browneye,browntown,bucketcunt,bunghole,butch,buttbreath,butthair,buttface,butthead,butthole,buttpicker,chink,christ,circlejerk,clit,cobia,cock,cocks,cocksuck,cocksucked,cocksucker,cocksucking,cocksucks,cornhole,cooter,crap,cum,cummer,cumming,cums,cumshot,cunilingus,cunillingus,cunnilingus,cunt,cuntlick,cuntlicker,cuntlicking,cunts,cyberfuc,dick,dike,dildo,dildos,dink,dinks,dong,dumbass,dyke,fag,fagget,fagging,faggit,faggot,faggs,fagot,fagots,fags,fart,farted,farting,fartings,farts,farty,fatass,fatso,felatio,fellatio,fuk,fuks,furburger,gangbang,gangbanged,gangbangs,gaysex,gazongers,goddamn,gonads,gook,guinne,hardon,hardcoresex,homo,hooker,horniest,horny,hotsex,hussy,jackass,jackingoff,jackoff,jack-off,jap,jerk,jerk-off,jesuschrist,jism,jiz,jizm,jizz,kike,knob,kock,kondum,kondums,kraut,kum,kummer,kumming,kums,kunilingus,lemonparty,lesbo,merde,mick,muff,nigger,niggers,orgasim,orgasims,orgasm,orgasms,pecker,penis,phonesex,phuk,phuked,phuking,phukked,phukking,phuks,phuq,pimp,piss,pissed,pisser,pissers,pisses,pissin,pissing,pissoff,porn,porno,pornos,prick,pricks,punk,pussies,pussy,pussys,queer,retard,schlong,screw,sheister,slag,sleaze,slut,sluts,smut,snatch,spunk,twat,wetback,whore,wop,nigga,boobs,tits,pussylips");
        $this->any = explode(',', "fuck,shit,douche");
        $this->text = strtolower($text);
    }

    public function isClean($text = null) {
        if (empty($text)) $text = $this->text;
        if (empty($text)) return true;
        $text = str_replace('[br]', ' ', $text);
        $words = explode(' ', strtolower($text));
        foreach ($words as $k => $v) {
            $test = trim(trim($v),".,;:'\"`~!@#$%^&*()></?");
            if (in_array($test, $this->full)) {
                return false;
            } else {
                foreach ($this->any as $a) {
                    if (stristr($v, $a)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
}
?>
