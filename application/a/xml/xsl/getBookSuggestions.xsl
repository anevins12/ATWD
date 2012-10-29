<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : getBookSuggestions.xsl
    Created on : 29 October 2012, 10:45
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

			<suggestionsfor>
				<xsl:value-of select="$book_id" />
			</suggestionsfor>

			<xsl:text>&#xa;</xsl:text>

			<books>

				<xsl:text>&#xa;</xsl:text>

				<suggestions>

				<xsl:text>&#xa;</xsl:text>

				<!-- display the course value -->
					<xsl:for-each select="//suggestions">

						<xsl:if test="(.)/@for-id = $book_id">

							<xsl:for-each select="item">

								<isbn>
									<!-- create an id attribute with the item's actual value  -->
									<xsl:attribute name="id">
										<xsl:value-of select="(.)" />
									</xsl:attribute>

									<!-- create a common attribute with the value of common attribute -->
									<xsl:attribute name="common">
										<xsl:value-of select="(.)/@common" />
									</xsl:attribute>

									<!-- create a before attribute with the value of before attribute -->
									<xsl:attribute name="before">
										<xsl:value-of select="(.)/@before" />
									</xsl:attribute>

									<!-- create a same attribute with the value of same attribute -->
									<xsl:attribute name="same">
										<xsl:value-of select="(.)/@same" />
									</xsl:attribute>

									<!-- create an after attribute with the value of after attribute -->
									<xsl:attribute name="after">
										<xsl:value-of select="(.)/@after" />
									</xsl:attribute>

									<!-- create a total attribute with the value of total attribute -->
									<xsl:attribute name="total">
										<xsl:value-of select="(.)/@total" />
									</xsl:attribute>

									<!-- get the item's isbn attribute value to use as the value of this node -->
									<xsl:value-of select="(.)/@isbn" />
								</isbn>

								<xsl:text>&#xa;</xsl:text>
								
							</xsl:for-each>

						<xsl:text>&#xa;</xsl:text>

						</xsl:if>

					</xsl:for-each>

				</suggestions>

				<xsl:text>&#xa;</xsl:text>

			</books>

			<xsl:text>&#xa;</xsl:text>
			
		</results>

    </xsl:template>

</xsl:stylesheet>