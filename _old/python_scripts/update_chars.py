import requests
baseurl = 'http://ffxivcollector.com/'
while True:
    page = requests.get(baseurl+'handler/charakter.php?update_mode=true&show=false').text
    print(page)
    if "Finish" in page:
        break

