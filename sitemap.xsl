<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:s="http://www.sitemaps.org/schemas/sitemap/0.9">
  <xsl:template match="/">
    <html><body>
      <h1>사이트맵</h1>
      <ul>
        <xsl:for-each select="s:urlset/s:url">
          <li>
            <xsl:value-of select="s:loc"/>
            (<xsl:value-of select="s:lastmod"/>)
          </li>
        </xsl:for-each>
      </ul>
    </body></html>
  </xsl:template>
</xsl:stylesheet>
