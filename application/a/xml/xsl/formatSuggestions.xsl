<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : formatSuggestions.xsl
    Created on : 04 January 2013, 11:16
    Author     : andrew
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>

    <!-- TODO customize transformation rules 
         syntax recommendation http://www.w3.org/TR/xslt 
    -->
    <xsl:template match="/">
		
		<div id="results">

		<h2>Suggested Books for Book ID <xsl:value-of select="//suggestionsfor" /></h2>
			<ul>
				<xsl:for-each select="//isbn">
					<li>
						<!-- create an id attribute with the value of item's id attribute  -->
						<xsl:attribute name="id">
							<xsl:value-of select="(.)/@item" />
						</xsl:attribute>
						<hgroup>
							<h3>ISBN: <xsl:value-of select="." /></h3>
						</hgroup>
					</li>
				</xsl:for-each>
			</ul>
		</div>

    </xsl:template>

</xsl:stylesheet>
