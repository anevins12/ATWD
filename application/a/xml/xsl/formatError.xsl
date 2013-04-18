<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : formatError.xsl
    Created on : 05 January 2013, 17:11
    Author     : andrew
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>

    <!-- TODO customize transformation rules 
         syntax recommendation http://www.w3.org/TR/xslt 
    -->
    <xsl:template match="error">
        <h2><xsl:value-of select="(.)/@message" /></h2>
        <h3>Error No. <xsl:value-of select="(.)/@id" /></h3>
    </xsl:template>

</xsl:stylesheet>
