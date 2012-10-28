<?xml version="1.0"?>

<!--
    Document   : getBooksByCourseId.xsl
    Created on : 28 October 2012, 12:44
    Author     : andrew
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="xml"/>

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
		<course>
			<xsl:value-of select="$course" />
		</course>
		<xsl:text>&#xa;</xsl:text>
		
		<books>
			
			<xsl:text>&#xa;</xsl:text>

			<!-- for each item -->
			<xsl:for-each select="//item">

				<!-- sort item by most borrowed -->
				<xsl:sort select="borrowedcount" order="descending" />

				<!-- construct a book element -->
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
						<xsl:value-of select="current()/borrowedcount" />
					</xsl:attribute>

				</book>

				<xsl:text>&#xa;</xsl:text>

			</xsl:for-each>

		</books>

	</results>

    </xsl:template>

</xsl:stylesheet>
