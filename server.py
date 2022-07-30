import requests
from fake_useragent import UserAgent
import vars

base_url = f"{vars.serverURI}/kandle/server.php"
# base_url = f"{vars.serverURI}/page/server.php"


def getLinks():
    headers = UserAgent().random
    # response = requests.get(
    #     f'{vars.serverURI}/python/server.php?get-links=1', headers={"Content-type": headers})
    response = requests.get(
        f'{base_url}?get-links=1', headers={"Content-type": headers})
    return response.json()


def saveOnServer(id=False, final=''):
    query = f"?set-link=1&id={id}&url={final}"

    headers = UserAgent().random

    response = requests.post(
        f'{base_url}{query}', headers={"Content-type": headers})

    return response.text


if __name__ == '__main__':
    # print(getLinks())
    saveOnServer()
