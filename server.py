import requests
from fake_useragent import UserAgent
import vars
import os
import json
# base_url = f"{vars.serverURI}/kandle/server.php"
base_url = f"{vars.serverURI}/Web/server.php"


def getLinks():
    headers = UserAgent().random
    response = requests.get(
        f'{base_url}?get-links=1', headers={"Content-type": headers})
    return response.json()


def saveOnServer(id=False, final=''):
    query = f"?set-link=1&id={id}&url={final}"
    headers = UserAgent().random
    response = requests.post(
        f'{base_url}{query}', headers={"Content-type": headers})
    return response.text


def saveChildNames():
    names = []
    with open(os.getcwd()+'\\renamer\\finalLinks.csv', 'r', encoding='utf-8') as file:
        n = file.readlines()
        for k in n:
            k = k.split(',')
            i = k[0].replace('\n', '')
            y = k[1].replace('\n', '')
            t = {'id': y, 'link': i}
            names.append(t)
        headers = UserAgent().random
        r = requests.post(base_url, json={'names': 1, 'childs': names}, headers={
                          "Content-type": headers})
        print(r.text)
        return r.text
    return False


if __name__ == '__main__':
    # print(getLinks())
    saveChildNames()
