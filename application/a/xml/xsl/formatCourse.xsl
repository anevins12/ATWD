<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : formatCourse.xsl
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

			<h2>Book with the Course ID <xsl:value-of select="//course" /></h2>
			<ul>
				<xsl:for-each select="//book">
					<li>
						<!-- create an id attribute with the value of item's id attribute  -->
						<xsl:attribute name="id">
							<xsl:value-of select="(.)/@id" />
						</xsl:attribute>
						<hgroup>
							<h4><xsl:value-of select="(.)/@title" /></h4>
							<h4>Isbn: <xsl:value-of select="(.)/@isbn" /></h4>
							<h4>Borrowed count: <xsl:value-of select="(.)/@borrowedcount" /></h4>
						</hgroup>
					</li>
				</xsl:for-each>
			</ul>

		</div>
		
    </xsl:template>



</xsl:stylesheet>
