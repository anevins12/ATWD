<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : updateBorrowedData.xsl
    Created on : 29 October 2012, 11:28
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
<!-- TESTING
<xsl:copy-of select="."/>
-->
		<results>

			<!-- new line -->
			<xsl:text>&#xa;</xsl:text>

			<!-- display the course value -->
			<xsl:for-each select="//item">

				<xsl:if test="(.)/@id = $book_id">

					<book>

						<!-- create an id attribute with the value of item's id attribute  -->
						<xsl:attribute name="id">
							<xsl:value-of select="current()/@id" />
						</xsl:attribute>

						<!-- create a title attribute with the value of title node -->
						<xsl:attribute name="title">
							<xsl:value-of select="title" />
						</xsl:attribute>

						<!-- create a name attribute with the value of name node -->
						<xsl:attribute name="isbn">
							<xsl:value-of select="current()/isbn" />
						</xsl:attribute>

						<!-- create a borrowedcount attribute with the value of borrowedcount node -->
						<xsl:attribute name="borrowedcount">
							<xsl:value-of select="current()/borrowedcount + 1" />
						</xsl:attribute>

					</book>

				<xsl:text>&#xa;</xsl:text>

				</xsl:if>

			</xsl:for-each>

		</results>

    </xsl:template>

</xsl:stylesheet>