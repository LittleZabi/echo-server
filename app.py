from scrapper import Parasite
from server import getChilds, getLinks, saveOnServer
import time
from vars import production
import os
from renamer import Renamer
requests_count = 0
if __name__ == '__main__':
    while True:
        requests_count += 1
        if production:
            os.system('CLS')
        print('Listening server....')
        print('requesting for ', requests_count, ' time')
        print()
        try:
            links = getLinks()
            # print('link: ', links)

            for link in links:
                break
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
            print('Childs: ', childs)
            if len(childs) > 0:
                renamer = Renamer(childs)

        except Exception as e:
            print('_-_-_- ERROR:', e)
        time.sleep(5)
