import requests
from lxml import html
f = open("page.html","r") #opens file with name of "test.txt"
page = f.read()

doc = html.fromstring(page)

for row in doc.cssselect('tr'):
	name = ""
	server = ""
	for cell in row.cssselect('td:nth-child(3)'):
		name = cell.text_content()
	for cell in row.cssselect('td:nth-child(4)'):
		server = cell.text_content()
	#print("https://ffxiv-collections-ley21.c9users.io/char?name="+name+"&server="+server)
	page = requests.get("https://ffxiv-collections-ley21.c9users.io/caller/update_charakter.php?name="+name+"&server="+server)
	print("Request char '"+name+"' from server '"+server+"'")
