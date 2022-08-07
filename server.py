from email import header
import requests
from fake_useragent import UserAgent
import vars
import os
import json
base_url = f"{vars.serverURI}/kandle/server.php"
# base_url = f"{vars.serverURI}/Web/server.php"


def getLinks():
    try:

        headers = UserAgent().random
        response = requests.get(
            f'{base_url}?get-links=1', headers={"Content-type": headers})
        return response.json()
    except Exception as e:
        print('[SERVER => getLinks] Error: ', e)
        return []


def getChilds():
    try:
        headers = UserAgent().random
        response = requests.get(
            f"{base_url}?get-childs=1", headers={'Content-type': headers})
        return response.json()
    except Exception as e:
        print('[SERVER => getChilds] Error: ', e)


def saveOnServer(id=False, final=''):
    query = f"?set-link=1&id={id}&url={final}"
    headers = UserAgent().random
    response = requests.post(
        f'{base_url}{query}', headers={"Content-type": headers})
    return response.text


def saveChildNames():
    print('called________________________')
    names = []

    finalLinks = '.\\finalLinks.csv'
    try:
        with open(finalLinks, 'r', encoding='utf-8') as file:
            n = file.readlines()
            for k in n:
                k = k.split(',')
                i = k[0].replace('\n', '')
                y = k[1].replace('\n', '')
                t = {'id': y, 'link': i}
                names.append(t)
            headers = UserAgent().random
            print('data: ', {'names': 1, 'childs': names})
            r = requests.post(base_url, json={'names': 1, 'childs': names}, headers={
                "Content-type": headers})
            print('Server Response: ', r.text)
            return r.text
        return False
    except Exception as e:
        print('[SERVER saveChildNames]', e)


if __name__ == '__main__':
    # print(getLinks())
    saveChildNames()
