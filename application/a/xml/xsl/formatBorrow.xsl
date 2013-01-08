<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : formatBorrow.xsl
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
     <xsl:template match="book">

		<div id="results">
			<ul>
				<xsl:for-each select="//book">
					<li>
						<ul>
							<li>
								<!-- create an id attribute with the value of item's id attribute  -->
								<xsl:attribute name="id">
									<xsl:value-of select="(.)/@id" />
								</xsl:attribute>
								<hgroup>
									<h3><xsl:value-of select="(.)/@title" /></h3>
									<h4>Isbn: <xsl:value-of select="(.)/@isbn" /></h4>
									<h4>Borrowed count: <xsl:value-of select="(.)/@borrowedcount" /></h4>
								</hgroup>
							</li>
						</ul>
					</li>
				</xsl:for-each>
			</ul>
		</div>

    </xsl:template>

</xsl:stylesheet>
