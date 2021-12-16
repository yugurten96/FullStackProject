export class Greeting {
  public "@id"?: string;

  constructor(_id?: string, public name?: string, public id?: number) {
    this["@id"] = _id;
  }
}
