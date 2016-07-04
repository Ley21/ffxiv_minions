import requests
from lxml import html
baseurl = ''
page = requests.get(baseurl+'/caller/update_charakter.php?numbers=true').text
doc = html.fromstring(page)
divs = doc.cssselect("a")
i = 0;
for link in divs:
    id = link.text_content()
    page = requests.get(baseurl+'/caller/update_charakter.php?id='+id).text
    print(page)

