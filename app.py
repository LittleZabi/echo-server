from scrapper import Parasite
from server import getLinks, saveOnServer
import time
import os

requests_count = 0
if __name__ == '__main__':
    while True:
        requests_count += 1
        os.system('CLS')
        print('Listening server....')
        print('requesting for ', requests_count, ' time')
        print()
        try:
            links = getLinks()
            # print('link: ', links)
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

        except Exception as e:
            print('_-_-_- ERROR:', e)
        time.sleep(5)
