from scrapper import Parasite
from server import getChilds, getLinks, saveOnServer
import time
from vars import production
import os
from renamer import Renamer
from vars import loading
count = 0
t = 0
if __name__ == '__main__':
    while True:
        if count >= 3:
            count = 0
        count += 1
        t += 1
        loading(before=f'Listening server [{t}]', scale=count)
        try:
            links = getLinks()
            for link in links:
                scrap = Parasite(linktype=link['cache'])
                scrap.CrackedMindBot(scrap.__filter__(
                    link['base_url']), open_only_browser=None)
                print("EndResult:", scrap.end_url, "Id:", link['id'])
                saveOnServer(
                    id=link["id"], final=scrap.end_url)
                try:
                    if len(link["base_url"].split("id=")) > 0:
                        base_link_id = link["base_url"].split("id=")[1]
                    else:
                        base_link_id = link["base_url"]
                    print(
                        f'ID: {link["id"]} | file ID: {base_link_id}')
                except:
                    print(f'ID: {link["id"]} | file ID: {link["base_url"]}')

                scrap.destroy()

            childs = getChilds()
            if len(childs) > 0:
                print('Renaming links: ', len(childs))
                renamer = Renamer(childs)

        except Exception as e:
            print('_-_-_- ERROR:', e)
        time.sleep(3)
