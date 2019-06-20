<script type="application/ld+json">
    {
        "@context":"http://schema.org",
        "@type":"WebSite",
        "url":"<? echo $url; ?>",
        "name" : "<? echo $name; ?>",
        "description" : "<? echo $description; ?>"
        <? if($SearchAction){ ?>
        ,
        "potentialAction":{
            "@type":"SearchAction",
            "target":"<? echo $SearchAction; ?>{query}",
            "query-input":"required name=query"
        }
        <? } ?>
        
   }
</script>